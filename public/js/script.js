let root = document.documentElement;
let opened = true;
// root.style.setProperty('--sidenav-width', '100px');

document.querySelector('.actionSidebar').addEventListener('click', () => {
    console.log('functiona');
    opened = !opened;
    document.querySelector('.sidebar').classList.toggle('active', opened);
    if (opened) {
        root.style.setProperty('--sidenav-width', '260px');
    } else {
        root.style.setProperty('--sidenav-width', '100px');
    }
});

document.querySelectorAll('nav.sidebar .options-nav>a.option').forEach(e => {
    if (e.href == window.location.href) {
        e.classList.add('active');
    }
});