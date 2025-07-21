<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>404 - Halaman Tidak Ditemukan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background: radial-gradient(ellipse at center, #000000 0%, #0a0a0a 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            user-select: none;
            text-align: center;
        }

        #canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            display: block;
        }

        h1 {
            font-size: 12rem;
            margin: 0;
            font-weight: 900;
            letter-spacing: 0.15em;
            color: #ff4c4c;
            text-shadow: 0 0 20px #ff4c4ccc;
            user-select: none;
        }

        p {
            font-size: 2rem;
            margin: 0.5em 0 1.5em;
            color: #ddd;
            max-width: 100%;
            user-select: none;
        }

        a.button {
            background-color: #ff4c4c;
            padding: 16px 36px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            box-shadow: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
            font-size: 1.2rem;
        }

        a.button:hover {
            background-color: #ff2222;
            box-shadow: 0 8px 24px rgba(255, 34, 34, 0.8);
        }

        /* Reset margin dan padding semua */
        h1,
        p,
        a {
            margin: 0;
            padding: 0;
            background: none;
            border: none;
            box-shadow: none;
        }

        /* Tambah spacing manual untuk p dan a */
        p {
            margin: 1rem 0 2rem;
        }

        a.button {
            box-shadow: 0 6px 18px rgba(255, 76, 76, 0.6);
            border-radius: 8px;
            padding: 16px 36px;
            display: inline-block;
        }

        h1,
        p,
        a.button {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body>

    <canvas id="canvas"></canvas>

    <h1>404</h1>
    <p>Maaf, halaman yang Anda cari tidak ditemukan.</p>
    <a href="{{ url('/dashboard') }}" class="button"><i class="fas fa-home"></i> Kembali ke Dashboard</a>

    <!-- FontAwesome for home icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.155.0/build/three.min.js"></script>

    <script>
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({
            canvas: document.getElementById('canvas'),
            alpha: true
        });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(window.devicePixelRatio);

        camera.position.z = 30;

        const textCanvas = document.createElement('canvas');
        const ctx = textCanvas.getContext('2d');
        const textSize = 150;
        textCanvas.width = 400;
        textCanvas.height = 200;
        ctx.font = `bold ${textSize}px 'Segoe UI'`;
        ctx.fillStyle = 'white';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('404', textCanvas.width / 2, textCanvas.height / 2);

        const imgData = ctx.getImageData(0, 0, textCanvas.width, textCanvas.height);
        const particlesPositions = [];
        const particlesColors = [];

        for (let y = 0; y < textCanvas.height; y += 4) {
            for (let x = 0; x < textCanvas.width; x += 4) {
                const index = (y * textCanvas.width + x) * 4;
                const alpha = imgData.data[index + 3];
                if (alpha > 128) {
                    const posX = x - textCanvas.width / 2;
                    const posY = -(y - textCanvas.height / 2);
                    const posZ = (Math.random() - 0.5) * 2;

                    particlesPositions.push(posX * 0.1, posY * 0.1, posZ);
                    particlesColors.push(1, 0.3, 0.3);
                }
            }
        }

        const geometry = new THREE.BufferGeometry();
        geometry.setAttribute('position', new THREE.Float32BufferAttribute(particlesPositions, 3));
        geometry.setAttribute('color', new THREE.Float32BufferAttribute(particlesColors, 3));

        const material = new THREE.PointsMaterial({
            size: 0.3,
            vertexColors: true,
            transparent: true,
            opacity: 0.85,
            blending: THREE.AdditiveBlending,
            depthWrite: false
        });

        const points = new THREE.Points(geometry, material);
        scene.add(points);

        let clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);

            let time = clock.getElapsedTime();

            points.rotation.y = time * 0.2;
            points.material.size = 0.25 + Math.sin(time * 5) * 0.05;

            renderer.render(scene, camera);
        }

        animate();

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
    </script>

</body>

</html>
