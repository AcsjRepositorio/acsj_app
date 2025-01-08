@props(['meals'])

<div class="container mt-5">
    <!-- Swiper container -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($meals as $meal)
                <div class="swiper-slide">
                    <x-cards.meal-week :meal="$meal" />
                </div>
            @endforeach
        </div>
        
        <!-- Setas de navegação (opcional) -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        
        <!-- Bolinhas de navegação (opcional) -->
        <div class="swiper-pagination"></div>
    </div>
</div>

<!-- CSS do Swiper -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"
/>

<!-- JS do Swiper -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  var swiper = new Swiper(".mySwiper", {
    loop: true,
    speed: 300,
    // quantos slides por vez (desktop)
    slidesPerView: 3,
    spaceBetween: 0, // espaçamento entre os cards
    // setas
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    // bolinhas
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    // breakpoints (responsividade)
    breakpoints: {
      992: {
        slidesPerView: 3, // >=992px
      },
      768: {
        slidesPerView: 2, // >=768px e <992px
      },
      0: {
        slidesPerView: 1, // <768px
      },
    },
  });
});
</script>

<!-- Estilos extras (opcional) -->
<style>
    /* Ajuste de layout do card */
    .swiper-slide {
        /* Se quiser controlar a altura, ou padding, etc. */
        display: flex;
        justify-content: center;
        align-items: center;
        width: 350px !important;
    }
    /* Setas padrão do Swiper */
    .swiper-button-next, .swiper-button-prev {
        color: #F25F29; 
        /* ou troque por outro estilo */
    }
    .swiper-button-next:hover, .swiper-button-prev:hover {
        color: #444444;
    }
    /* Bolinhas padrão do Swiper */
    .swiper-pagination-bullet {
        background: #F25F29;
    }
    .swiper-pagination-bullet-active {
        background: #444444;
    }

</style>
