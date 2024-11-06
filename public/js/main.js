document.addEventListener('DOMContentLoaded', function() {
    const swipers = document.querySelectorAll('.swiper-container');
    
    swipers.forEach(function(element) {
        new Swiper(element, {
            slidesPerView: 1,
            spaceBetween: 10,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 40,
                },
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const swiperConfig = {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        breakpoints: {
            // quando a largura da janela é >= 320px
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            // quando a largura da janela é >= 768px
            768: {
                slidesPerView: 3,
                spaceBetween: 30
            },

        }
    };

    // Inicializa todos os Swipers
    document.querySelectorAll('.swiper-container').forEach(function(element) {
        new Swiper(element, swiperConfig);
    });
});