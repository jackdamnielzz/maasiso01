let scene, camera, renderer, stars = [], isoStars = [], logo;
let raycaster, mouse, controls;
let selectedStar = null;

const isoStandards = [
    { name: "ISO 9001", description: "Kwaliteitsmanagement", position: new THREE.Vector3(2, 2, 0) },
    { name: "ISO 27001", description: "Informatiebeveiliging", position: new THREE.Vector3(-2, -2, 0) },
    { name: "ISO 14001", description: "Milieumanagement", position: new THREE.Vector3(-2, 2, 0) },
    { name: "ISO 45001", description: "Gezond en veilig werken", position: new THREE.Vector3(2, -2, 0) }
];

function init() {
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    renderer = new THREE.WebGLRenderer({
        canvas: document.getElementById('galaxy-canvas'),
        antialias: true
    });
    renderer.setSize(window.innerWidth, window.innerHeight);

    camera.position.z = 5;
    controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;

    raycaster = new THREE.Raycaster();
    mouse = new THREE.Vector2();

    createStars();
    createISOStars();
    createLogo();
    createGalaxyBackground();

    animate();

    window.addEventListener('resize', onWindowResize, false);
    window.addEventListener('mousemove', onMouseMove, false);
    window.addEventListener('click', onMouseClick, false);
}

function createStars() {
    const geometry = new THREE.SphereGeometry(0.05, 32, 32);
    const material = new THREE.MeshBasicMaterial({ color: 0xFFFFFF });

    for (let i = 0; i < 1000; i++) {
        const star = new THREE.Mesh(geometry, material);
        star.position.set(
            Math.random() * 100 - 50,
            Math.random() * 100 - 50,
            Math.random() * 100 - 50
        );
        scene.add(star);
        stars.push(star);
    }
}

function createISOStars() {
    const geometry = new THREE.SphereGeometry(0.2, 32, 32);
    const material = new THREE.MeshPhongMaterial({ color: 0xD4AF37 });

    isoStandards.forEach(standard => {
        const star = new THREE.Mesh(geometry, material);
        star.position.copy(standard.position);
        star.userData = { name: standard.name, description: standard.description };
        scene.add(star);
        isoStars.push(star);
    });

    const light = new THREE.PointLight(0xFFFFFF, 1, 100);
    light.position.set(0, 0, 10);
    scene.add(light);
}

function createLogo() {
    const geometry = new THREE.CircleGeometry(0.5, 32);
    const material = new THREE.MeshBasicMaterial({ color: 0x4A9B8F });
    logo = new THREE.Mesh(geometry, material);
    logo.position.set(0, 0, 0);
    scene.add(logo);
}

function createGalaxyBackground() {
    const geometry = new THREE.PlaneGeometry(100, 100);
    const material = new THREE.ShaderMaterial({
        uniforms: {
            time: { value: 0 },
            resolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) }
        },
        vertexShader: `
            varying vec2 vUv;
            void main() {
                vUv = uv;
                gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
            }
        `,
        fragmentShader: `
            uniform float time;
            uniform vec2 resolution;
            varying vec2 vUv;

            void main() {
                vec2 position = vUv * 2.0 - 1.0;
                float r = length(position) * 2.0;
                float a = atan(position.y, position.x);
                float intensity = 0.15 / (0.1 + r);

                vec3 color = vec3(0.1, 0.2, 0.3);
                color += 0.5 + 0.5 * cos(time + a * 5.0 + vec3(0, 2, 4));
                color *= intensity;

                gl_FragColor = vec4(color, 1.0);
            }
        `,
        side: THREE.DoubleSide
    });

    const mesh = new THREE.Mesh(geometry, material);
    mesh.position.z = -10;
    scene.add(mesh);
}

function animate() {
    requestAnimationFrame(animate);

    controls.update();

    stars.forEach(star => {
        star.rotation.x += 0.001;
        star.rotation.y += 0.001;
    });

    isoStars.forEach(star => {
        star.scale.x = star.scale.y = star.scale.z = 1 + 0.1 * Math.sin(Date.now() * 0.001 + star.position.x);
    });

    logo.rotation.z += 0.005;

    if (selectedStar) {
        camera.position.lerp(selectedStar.position, 0.05);
        camera.lookAt(selectedStar.position);
    }

    const galaxyMaterial = scene.children.find(child => child.type === 'Mesh' && child.material.type === 'ShaderMaterial').material;
    galaxyMaterial.uniforms.time.value += 0.01;

    renderer.render(scene, camera);
}

function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
}

function onMouseMove(event) {
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = - (event.clientY / window.innerHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);

    const intersects = raycaster.intersectObjects(isoStars);

    if (intersects.length > 0) {
        document.body.style.cursor = 'pointer';
        showTooltip(intersects[0].object, event.clientX, event.clientY);
    } else {
        document.body.style.cursor = 'default';
        hideTooltip();
    }
}

function onMouseClick(event) {
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = - (event.clientY / window.innerHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);

    const intersects = raycaster.intersectObjects(isoStars);

    if (intersects.length > 0) {
        selectedStar = intersects[0].object;
        enterExplorationMode(selectedStar);
    } else {
        exitExplorationMode();
    }
}

function showTooltip(star, x, y) {
    const tooltip = document.getElementById('tooltip') || createTooltip();
    tooltip.style.left = `${x + 10}px`;
    tooltip.style.top = `${y + 10}px`;
    tooltip.innerHTML = `<h3>${star.userData.name}</h3><p>${star.userData.description}</p>`;
    tooltip.style.display = 'block';
}

function hideTooltip() {
    const tooltip = document.getElementById('tooltip');
    if (tooltip) {
        tooltip.style.display = 'none';
    }
}

function createTooltip() {
    const tooltip = document.createElement('div');
    tooltip.id = 'tooltip';
    tooltip.style.position = 'absolute';
    tooltip.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
    tooltip.style.color = '#fff';
    tooltip.style.padding = '10px';
    tooltip.style.borderRadius = '5px';
    tooltip.style.pointerEvents = 'none';
    tooltip.style.zIndex = '1000';
    document.body.appendChild(tooltip);
    return tooltip;
}

function enterExplorationMode(star) {
    controls.enabled = false;
    showStarInfo(star);
}

function exitExplorationMode() {
    selectedStar = null;
    controls.enabled = true;
    hideStarInfo();
}

function showStarInfo(star) {
    const infoPanel = document.getElementById('info-panel') || createInfoPanel();
    infoPanel.innerHTML = `
        <h2>${star.userData.name}</h2>
        <p>${star.userData.description}</p>
        <button onclick="exitExplorationMode()">Terug naar Melkweg</button>
    `;
    infoPanel.style.display = 'block';
}

function hideStarInfo() {
    const infoPanel = document.getElementById('info-panel');
    if (infoPanel) {
        infoPanel.style.display = 'none';
    }
}

function createInfoPanel() {
    const infoPanel = document.createElement('div');
    infoPanel.id = 'info-panel';
    infoPanel.style.position = 'absolute';
    infoPanel.style.top = '20px';
    infoPanel.style.right = '20px';
    infoPanel.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
    infoPanel.style.color = '#fff';
    infoPanel.style.padding = '20px';
    infoPanel.style.borderRadius = '10px';
    infoPanel.style.zIndex = '1000';
    document.body.appendChild(infoPanel);
    return infoPanel;
}

init();
