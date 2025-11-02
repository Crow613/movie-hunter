
$(document).ready(function(){
	
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
        })
        .blur(function(){
            if(this.value=='') {
                this.value = this.title;                    
			}
		})
	$.ajax({
		url: '/ajax.php',
		type: 'GET',
		data: {
			className: '\\App\\Http\\Controllers\\Api\\MovieApiController',
			method: 'getLatest',
			data: {}
		},
		dataType: 'json',
		success: function(response) {
			if (response) {
				let html = ``;
				response.map(item=>{
					const calc = item.rating / 10 ;
					console.log(item.image)
					html +=`<div class="movie">
                     <div class="movie-image"> 
                     <span class="play">
                     		<span class="name">${item.name}</span>
                     </span> 
                     <a href="#">
                          <img src="${item.image}" alt="" />
                     </a> </div>
                     <div class="rating">
                         <p>RATING</p>
                         <div class="stars" >
                             <div class="stars-in" style="width: ${calc}%" > </div>
                         </div>
                         <span class="comments">12</span> </div>
						 </div>`
				})
				html +=`<div class=\"cl\">&nbsp;</div>`
				$("#latest").append(html);
			}
		},
		error: function(xhr, status, error) {
			console.error('Ошибка AJAX:', error);
			$('#result').html('<p>Произошла ошибка</p>');
		}
	});
});
