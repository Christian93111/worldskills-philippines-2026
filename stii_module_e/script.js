let photos = [];

const elements = {
    browseBtn: document.getElementById('browseBtn'),
    loadSample: document.getElementById('loadSampleBtn'),
    fullscreen: document.getElementById('fullScreenBtn'),
    dropArea: document.getElementById('drop-area'),
    operatingMode: document.getElementById('operatingMode'),
    themeMode: document.getElementById('themeMode'),
    slide: document.getElementById('slide'),
    photoList: document.getElementById('photoList'),
    fileInput: document.getElementById('fileInput'),
};

const state = {
    currentIndex: 0,
    timer: 0,
    operating: 'manual',
    theme: 'a'
};

const samplePhotos = [
    'samplephotos/basilique-notre-dame-de-fourviere-lyon.jpg',
    'samplephotos/beautiful-view-in-lyon.jpg',
    'samplephotos/place-bellecour-lyon.jpg',
    'samplephotos/tour-metalique-lyon.jpg'
];

function getCaption(filename) {
    const name = filename.split('/').pop().split('.')[0];
    return name.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
}

function showSlide(index) {
    if (photos.length === 0) {
        return;
    } 

    state.currentIndex = (index + photos.length) % photos.length;
    src = photos[state.currentIndex];

    elements.slide.className = '';
    elements.slide.classList.add(`theme-${state.theme}`);

    let content = '';

    if (state.theme === 'c') {
        caption = getCaption(src).split(' ').map((word, i) => `<span style="animation-delay:${i * 0.3}s">${word}</span>`).join(' ');
        content = `
            <div class="slide-content theme-c-anim">
                <img src="${src}">
                <p class="caption">${caption}</p>
            </div>
        `;
    }

    else {
        content = `
            <div class="slide-content">
                <img src="${src}">
                ${state.theme !== 'e' ? `<p class="caption">${getCaption(src)}</p>` : ''}
            </div>
        `;
    }

    elements.slide.innerHTML = content;
}

function renderPhotoList() {
    elements.photoList.innerHTML = '';

    photos.forEach((src, index) => {
        const img = document.createElement('img');
        img.src = src;
        img.style.width = '80px';
        img.style.margin = '5px';
        img.draggable = true;

        img.addEventListener('click', () => showSlide(index));

        img.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', index);
        });

        img.addEventListener('drop', (e) => {
            e.preventDefault();
            const from = e.dataTransfer.getData('text/plain');
            const to = index;

            const temp = photos[from];
            photos[from] = photos[to];
            photos[to] = temp;

            renderPhotoList();
        });

        img.addEventListener('dragover', (e) => e.preventDefault());

        elements.photoList.appendChild(img);
    });
}

function addPhotos(files) {
    const fileArray = Array.from(files);

    fileArray.forEach(file => {
        const url = URL.createObjectURL(file);
        photos.push(url);
    });

    renderPhotoList();
    showSlide(0);
}

elements.loadSample.addEventListener('click', () => {
    photos = [...samplePhotos];
    renderPhotoList();
    showSlide(0);
});

elements.browseBtn.addEventListener('click', () => {
    elements.fileInput.click();
});

elements.fileInput.addEventListener('change', (e) => {
    addPhotos(e.target.files);
});

elements.operatingMode.addEventListener('change', (e) => {
    state.operating = e.target.value;
    startMode();
});

function startMode() {
    stopAuto();

    if (state.operating === 'auto') {
        state.timer = setInterval(() => {
            showSlide(state.currentIndex + 1);
        }, 3000);
    }

    if (state.operating === 'random') {
        state.timer = setInterval(() => {
            const rand = Math.floor(Math.random() * photos.length);
            showSlide(rand);
        }, 3000);
    }
}

function stopAuto() {
    if (state.timer) {
        clearInterval(state.timer);
        state.timer = null;
    }
}

elements.themeMode.addEventListener('change', (e) => {
    state.theme = e.target.value;
    showSlide(state.currentIndex);
});

window.addEventListener('keydown', (event) => {
    if (state.operating !== 'manual') return;

    if (event.key === 'ArrowRight') {
        showSlide(state.currentIndex + 1);
    } 
    
    else if (event.key === 'ArrowLeft') {
        showSlide(state.currentIndex - 1);
    }
});

elements.fullscreen.addEventListener('click', () => {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    } 
    
    else {
        document.documentElement.requestFullscreen();
    }
});

elements.dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
});

elements.dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    addPhotos(e.dataTransfer.files);
});