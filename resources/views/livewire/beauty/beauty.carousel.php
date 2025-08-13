 {{-- owl-carousel --}}
   <!-- Include Owl Carousel CSS and JS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
  
 <div class="container">
 <div class="owl-carousel owl-theme vertical-slider">
        <div class="item">
            <div class="carousel-item-card">
                <img src="{{ asset('assets-file/img/adv/b-girl.jpg') }}" alt="Beauty Service">
                <div class="carousel-content">
                    <h4>Beauty Services</h4>
                    <p>Professional beauty treatments</p>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="carousel-item-card">
                <img src="{{ asset('assets-file/img/adv/dog.jpg') }}" alt="Pet Services">
                <div class="carousel-content">
                    <h4>Pet Care</h4>
                    <p>Safe and reliable pet services</p>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="carousel-item-card">
                <img src="{{ asset('assets-file/img/adv/store.jpg') }}" alt="Shopping">
                <div class="carousel-content">
                    <h4>Safe Shopping</h4>
                    <p>Secure online shopping experience</p>
                </div>
            </div>
        </div>
    </div>
</div>

 <script>
    // Offer Slider JS
	$('.offer-slider').owlCarousel({
		loop: true,
		margin: 15,
		nav: true,
		dots: false,
		smartSpeed: 1000,
		autoplay: true,
		autoplayTimeout: 4000,
		autoplayHoverPause: true,
		rtl: false,
		navText: [
			"<i class='bx bx-left-arrow-alt'></i>",
			"<i class='bx bx-right-arrow-alt'></i>"
		],
		responsive:{
			0:{
				items: 1,
			},
			768:{
				items: 2,
			},
			992:{
				items: 5,
			}
		}
    });
</script>