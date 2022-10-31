const menuBtn = document.querySelector('#menu-btn');
const navbar = document.querySelector('.navbar');
const header = document.querySelector('.header');

menuBtn.addEventListener('click', () => {
    menuBtn.classList.toggle('fa-times');
    navbar.classList.toggle('active');
    header.classList.toggle('active');
});

const info = document.querySelector('.info-container');
const counters = document.querySelectorAll('.counter');
let bol = false;

window.addEventListener('scroll', () => {
    menuBtn.classList.remove('fa-times');
    navbar.classList.remove('active');
    // Change background in header
    if (window.scrollY > 800) {
        header.classList.add('active');
    } else {    
        header.classList.remove('active');
    }

    // Animation of counting in info container
    const sectionOffset = info.offsetTop + info.offsetHeight;
    const pageOffset = window.innerHeight + pageYOffset;
    if (pageOffset > sectionOffset && bol === false) {
        counters.forEach((counter) => {
            function updateCount() {
                const target = + counter.getAttribute('data-target');
                const speed = + counter.getAttribute('data-speed');
                const count = + counter.innerText;
                if (count < target) {
                    counter.innerText = count + 1;
                    setTimeout(updateCount, speed);
                } else { 
                    counter.innerText = target;
                }
            }
            updateCount();
            bol = true;
        });
    }
});

// Reviews swiper
const reviewsSwiper = new Swiper(".reviews-slider", {
    spaceBetween: 20,
    grabCursor: true,
    loop: true,
    autoplay: {
        delay: 3500,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {
       540: {slidesPerView: 1,},
       768: {slidesPerView: 2,},
       1024: {slidesPerView: 3,},
    },
});

// Email for NewsLetter
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message'),
      contactUser = document.getElementById('contact-user');
const sendEmail = (e) => {
    e.preventDefault();
    //Check if Field has Value
    if(contactUser.value === '') {
        contactMessage.classList.remove('color-green');
        contactMessage.classList.add('color-red');
        contactMessage.textContent = 'You Must Enter Your Email';
        setTimeout(() => {
            contactMessage.textContent = '';
        }, 3000);
    } else {
        emailjs.sendForm('service_qypozmg', 'template_u72uupy', '#contact-form', '_Fg9sxubNkeqZst-c')
            .then(() => {
                contactMessage.textContent = 'Subscribed';
                setTimeout(() => {
                    contactMessage.textContent = '';
                }, 3000);
            }, (error) => {
                alert("OPPS!!! Something went Wrong", error);
            });
        //Clear Input Field
        contactUser.value = '';
    }
}
contactForm.addEventListener('submit', sendEmail);
