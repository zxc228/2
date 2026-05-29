let idx = 0;
const slides = document.querySelectorAll('.slide');

if (slides.length > 0) {
    function show(n) {
        slides.forEach(s => s.classList.remove('active'));
        idx = (n + slides.length) % slides.length;
        slides[idx].classList.add('active');
    }
    function nextSlide() { show(idx + 1); }
    function prevSlide() { show(idx - 1); }

    setInterval(nextSlide, 3000);
}

function filterTable() {
    let input = document.getElementById("adminSearch").value.toLowerCase();
    let rows = document.querySelectorAll(".order-row");
    
    rows.forEach(row => {
        let fio = row.querySelector(".search-fio").textContent.toLowerCase();
        let course = row.querySelector(".search-course").textContent.toLowerCase();
        
        if (fio.includes(input) || course.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

const toast = document.getElementById('toast');
if (toast) {
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
