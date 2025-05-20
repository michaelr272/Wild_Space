document.addEventListener("wheel",function(event){
    if (event.ctrlKey){event.preventDefault();
    }
}, {passive:false});
function setScale(){
    const scale = window.innerWidth / document.body.clientWidth;
    document.body.clientWidht;document.body.style.transform = 'scale(${scale})';
}
window.addEventListener('resize', setScale);
window.addEventListener('load', setScale)