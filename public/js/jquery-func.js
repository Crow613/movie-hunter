
$(document).ready(function(){
	function searchMovies()
	{
		const btnSearch = $(".search-button");
			btnSearch.on("click", function (b){
				b.preventDefault();
				getCreateDiv()
				const inputSearch = $("#search-field");
				console.log(inputSearch)
				const valueInput = inputSearch.val().trim()
				getRequest(
							'\\App\\Http\\Controllers\\Api\\MovieApiController',
							'getMovieByName',
							"#box",
							{
								name: valueInput,
							}
						);
			});

	}
	function getAllMovies()
	{
		const showAll = $("#show-all");
		showAll.on("click",()=>{
			getCreateDiv()
			getRequest(
				'\\App\\Http\\Controllers\\Api\\MovieApiController',
				'getMovieByName',
				"#box",
				{
					name: "",
				}
			);
		})

	}

	function getMovies(className, method, blockId, limit = 6){
		getRequest(
			className, method, blockId,
			{ limit: limit,}
		)
	}
	setTimeout(()=>{
		$(".movie-image").hover(function(){
				$(this).find(".play").show();
			},
			function()
			{
				$(this).find(".play").hide();
			});
		$(".blink").focus(function() {
			if(this.title==this.value) {
				this.value = '';
			}
		}).blur(function(){
			if(this.value=='') {
				this.value = this.title;
			}
		})
	},100)
	function getRequest(controller, method, blockId, data = {}){
			return 	$.ajax({
				url: '/ajax.php',
				type: 'GET',
				data: {
					className: controller,
					method: method,
					data: data
				},
				dataType: 'json',
				success:  (response) => {
					if(response.length  > 0) {
						setTimeout(() => htmlGenerate(response, blockId), 200)
					}
				},
				error: function(xhr, status, error) {
					console.error('Ошибка AJAX:', error);
					$('#result').html('<p>Произошла ошибка</p>');
				}
			});
	}
	function htmlGenerate(items, boxId)
	{
		let html = ``;
		items.map((item, index )=> {
			const calc = item.rating / 10 ;
             let last = ""
				index++;
			const check = Number.isInteger((index / 6))
			if(check ){
				last = " last"
			}
			html +=`<div class="movie ${last}">
			                 <div class="movie-image">
			                 <span class="play">
			                        <a href="/movie/${item.id}">
			                 		<span class="name">${item.name}</span>
			                 		</a>
			                 </span>
								 <a href="/movie/${item.id}">
									  <img src="${item.image}" alt="" />
								 </a> 
			                 </div>
			                 <div class="rating">
			                     <p>RATING</p>
			                     <div class="stars" >
			                         <div class="stars-in" style="width: ${calc}%" > </div>
			                     </div>
			                     <span class="comments">12</span> </div>
								 </div>`})
		html +=`<div class="cl">&nbsp;</div>`
		const movieLatest = $(boxId);
		movieLatest.append(html);
	}
	function  getCreateDiv()
	{
		$("#content, #news, #coming, #box").remove()
		const div  = document.createElement("div")
		div.setAttribute("id", "content");
		main.append(div);
		const div2 = document.createElement("div");
		div2.setAttribute("class", "box");
		div2.setAttribute("id", "box");
		$('#content').append(div2);
	}
	if (location.pathname === "/"){
		$("#latest-trailers-btn").on("click", ()=> {
			getCreateDiv()
			getRequest(
				'\\App\\Http\\Controllers\\Api\\MovieApiController',
				'getLatest',
				"#content",
				{
					limit:  6,
					sort: "DESC"
				}
			);
		})
		$("#top-rated-btn").on("click", ()=> {
			getCreateDiv()
			getRequest(
				'\\App\\Http\\Controllers\\Api\\MovieApiController',
				'getTop',
				"#content",
				{
					limit: 6,
				}
			)
		})

		$("#most-commented-btn").on("click", ()=> {
			getCreateDiv()
			getRequest(
				'\\App\\Http\\Controllers\\Api\\MovieApiController',
				'getMostCommented',
				"#content",
				{limit: 6 }
			)

		})

		getRequest(
			'\\App\\Http\\Controllers\\Api\\MovieApiController',
			'getLatest',
			"#latest-trailers",
			{
				limit:  6,
				sort: "DESC"
			}
		);
		getRequest(
			'\\App\\Http\\Controllers\\Api\\MovieApiController',
			'getTop',
			"#top-rated",
			{
				limit: 6,
			}
		)
		getRequest(
			'\\App\\Http\\Controllers\\Api\\MovieApiController',
			'getMostCommented',
			"#most-comments",
			{limit: 6 }
		)
		getAllMovies()
		searchMovies()
	}
});
