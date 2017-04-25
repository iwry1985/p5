$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();	

		$('.slider_header').slick({
		  infinite: false,
		  slidesToShow: 4,
		  slidesToScroll: 4,
		   responsive: [
		    {
		      breakpoint: 700,
		      settings: {
		        slidesToShow: 3,
		        slidesToScroll: 3
		      }
		    },
		    {
		      breakpoint: 500,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 2
		      }
		    },
		    {
		      breakpoint: 300,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
 		 ]
		});



	$('.profil_slider').slick({
		infinite: false,
		slidesToShow: 6,
		slidesToScroll: 6,
		responsive: [
		    {
		      breakpoint: 1000,
		      settings: {
		        slidesToShow: 5,
		        slidesToScroll: 5
		      }
		    },
		    {
		      breakpoint: 750,
		      settings: {
		        slidesToShow: 4,
		        slidesToScroll: 4
		      }
		    },
		    {
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 3,
		        slidesToScroll: 3
		      }
		    },
		    {
		      breakpoint: 350,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 2
		      }
		    },
		    {
		      breakpoint: 250,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
 		 ]
		});
	

});

