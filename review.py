#!/usr/bin/env python3
"""
AI Code Review Script with GitHub PR Comments
---------------------------------------------
Использует LangChain + Google Gemini LLM для анализа кода проекта.
Автоматически добавляет комментарии к Pull Request через GitHub API.
"""
import os
import argparse
from pathlib import Path
import logging
from dotenv import load_dotenv
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain_core.messages import HumanMessage
from langchain_core.runnables import RunnableConfig
from langchain_core.exceptions import LangChainException
from tqdm import tqdm
from concurrent.futures import ThreadPoolExecutor, as_completed
from github import Github

# ------------------------------
# Настройка логирования
# ------------------------------
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s",
    datefmt="%H:%M:%S"
)
logger = logging.getLogger(__name__)

# ------------------------------
# Загружаем переменные окружения
# ------------------------------
load_dotenv()
GOOGLE_API_KEY = os.getenv("GOOGLE_API_KEY")
GITHUB_TOKEN = os.getenv("GITHUB_TOKEN")  # Для GitHub Actions
GITHUB_REPOSITORY = os.getenv("GITHUB_REPOSITORY")  # owner/repo
PR_NUMBER = os.getenv("PR_NUMBER")  # номер Pull Request

if not GOOGLE_API_KEY:
    raise ValueError("Set your GOOGLE_API_KEY in .env file")

# ------------------------------
# Парсинг аргументов
# ------------------------------
parser = argparse.ArgumentParser(description="AI Code Review via Gemini with GitHub PR")
parser.add_argument("--project_dir", default=".", help="Путь к проекту")
parser.add_argument(
    "--extensions", nargs="+", default=[".py", ".js", ".php", ".vue", ".html", ".css"],
    help="Расширения файлов для анализа"
)
parser.add_argument(
    "--exclude_dirs", nargs="+", default=[".git", "node_modules", "venv", "vendor", "_docker"],
    help="Директории для исключения"
)
parser.add_argument("--max_tokens", type=int, default=4000, help="Максимальная длина кода для LLM")
parser.add_argument("--model", default="gemini-2.5-flash", help="Модель LLM для анализа")
parser.add_argument("--output", help="Сохранять результаты в файл")
args = parser.parse_args()

PROJECT_DIR = args.project_dir
EXTENSIONS = tuple(args.extensions)
EXCLUDE_DIRS = args.exclude_dirs
MAX_CODE_LENGTH = args.max_tokens

# ------------------------------
# Настройка LLM
# ------------------------------
llm = ChatGoogleGenerativeAI(
    model=args.model,
    temperature=0,
    max_retries=2,
)

# ------------------------------
# GitHub Helper
# ------------------------------
def post_pr_comment(file_path: str, review_text: str):
    """Добавляет комментарий в Pull Request на GitHub"""
    if not all([GITHUB_TOKEN, GITHUB_REPOSITORY, PR_NUMBER]):
        logger.warning("GitHub credentials not found. Skipping PR comment.")
        return
    try:
        g = Github(GITHUB_TOKEN)
        repo = g.get_repo(GITHUB_REPOSITORY)
        pr = repo.get_pull(int(PR_NUMBER))
        comment_body = f"### AI Review for `{file_path}`\n\n{review_text[:600]}..."  # Ограничение длинного комментария
        pr.create_issue_comment(comment_body)
        logger.info(f"Комментарий для {file_path} добавлен в PR #{PR_NUMBER}")
    except Exception as e:
        logger.error(f"Не удалось добавить комментарий для {file_path}: {e}")

# ------------------------------
# Функции работы с файлами
# ------------------------------
def get_code_files(root_dir: str) -> list[str]:
    """Рекурсивный поиск файлов с нужными расширениями, исключая директории"""
    code_files = []
    root_path = Path(root_dir).resolve()
    for path in root_path.rglob("*"):
        if path.is_file() and path.suffix in EXTENSIONS:
            if not any(exclude in path.parts for exclude in EXCLUDE_DIRS):
                code_files.append(str(path))
    return code_files

def read_file(file_path: str) -> str:
    """Безопасное чтение файла с игнорированием ошибок кодировки"""
    try:
        return Path(file_path).read_text(encoding="utf-8", errors="ignore")
    except Exception as e:
        logger.warning(f"Не удалось прочитать файл {file_path}: {e}")
        return ""

# ------------------------------
# Функция анализа кода через Gemini
# ------------------------------
def review_code(file_path: str, file_content: str) -> str:
    """Отправка кода в LLM и получение ревью"""
    content_to_review = file_content[:MAX_CODE_LENGTH]
    messages = [
        HumanMessage(
            content=f"Проанализируй этот код и предложи улучшения, рекомендации, безопасность, читаемость:\n\n{content_to_review}"
        )
    ]
    try:
        ai_msg = llm.invoke(messages, config=RunnableConfig(timeout=120))
        return ai_msg.content
    except LangChainException as e:
        return f"⚠️ Ошибка LLM для {file_path}: {e}"
    except Exception as e:
        return f"⚠️ Неизвестная ошибка для {file_path}: {e}"

# ------------------------------
# Основной цикл
# ------------------------------
def main():
    logger.info(f"🔍 Начинаю AI Code Review для '{PROJECT_DIR}'...")
    logger.info(f"Используемая модель: {args.model}")
    logger.info(f"Расширения файлов: {EXTENSIONS}")
    logger.info(f"Исключаемые директории: {EXCLUDE_DIRS}")

    files = get_code_files(PROJECT_DIR)
    if not files:
        logger.warning("🤷 Кодовые файлы для анализа не найдены.")
        return

    results = []
    MAX_WORKERS = 5
    with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
        future_to_file = {executor.submit(read_file, f): f for f in files}
        for future in tqdm(as_completed(future_to_file), total=len(files), desc="Обработка файлов"):
            file = future_to_file[future]
            try:
                code = future.result()
                if not code:
                    results.append((file, f"⚠️ Не удалось прочитать файл {file}."))
                    continue
                review = review_code(file, code)
                results.append((file, review))
                # Публикуем комментарий в PR
                post_pr_comment(file, review)
            except Exception as e:
                results.append((file, f"⚠️ Ошибка анализа {file}: {e}"))

    # Вывод результатов
    output_content = []
    for file, review_text in results:
        header = f"\n--- Review for {file} ---\n"
        print(header)
        print(review_text)
        output_content.append(header + review_text)

    if args.output:
        try:
            with open(args.output, "w", encoding="utf-8") as f:
                f.write("\n".join(output_content))
            logger.info(f"✅ Результаты сохранены в файл: {args.output}")
        except Exception as e:
            logger.error(f"⚠️ Ошибка сохранения результатов: {e}")

if __name__ == "__main__":
    main()