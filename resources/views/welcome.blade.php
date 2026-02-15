<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KianToT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Syne:wght@700;800&family=Sedgwick+Ave+Display&family=Permanent+Marker&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --accent: #a855f7;
            --accent-glow: rgba(168, 85, 247, 0.4);
            --bg-dark: #000;
            --card-bg: rgba(0, 0, 0, 0.4);
            --card-border: rgba(255, 255, 255, 0.1);
        }

        * {
            cursor: none !important;
        }

        body {
            margin: 0;
            background-color: var(--bg-dark);
            color: white;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Custom Cursor */
        #custom-cursor {
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 10001;
            box-shadow: 0 0 10px var(--accent), 0 0 20px var(--accent);
            transform: translate(-50%, -50%);
            transition: width 0.2s, height 0.2s;
        }

        #cursor-canvas {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 10000;
        }

        /* GIF Background */
        .gif-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            background-image: url('{{ asset("GIF/1106.gif") }}');
            background-size: cover;
            background-position: center;
            filter: brightness(0.5) contrast(1.2);
        }

        /* Overlay to ensure readability */
        .overlay {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: radial-gradient(circle, transparent 20%, rgba(0,0,0,0.8) 100%);
        }

        /* Splash Screen */
        #splash {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4); /* Semi-transparent black */
            backdrop-filter: blur(25px); /* Heavy blur to see the GIF background */
            -webkit-backdrop-filter: blur(25px);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .enter-text {
            font-family: 'Sedgwick Ave Display', cursive;
            font-size: 2.5rem;
            letter-spacing: 0.2rem;
            color: var(--accent);
            text-shadow: 0 0 20px var(--accent);
            animation: glitch-pulse 2s infinite ease-in-out;
        }

        @keyframes glitch-pulse {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        /* Main Container */
        .main-stack {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: opacity 1s ease, visibility 1s ease;
        }

        .main-stack.visible {
            opacity: 1;
            visibility: visible;
        }

        /* Graffiti Title */
        .title {
            font-family: 'Permanent Marker', cursive;
            font-size: 7rem;
            margin: 0;
            position: relative;
            color: white;
            text-shadow: 0 0 10px var(--accent),
                         0 0 20px var(--accent),
                         0 0 40px var(--accent);
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .title:hover {
            transform: scale(1.05) rotate(-2deg);
        }

        .subtitle {
            font-size: 1.3rem; /* Slightly larger */
            color: rgba(255, 255, 255, 0.95); /* Nearly solid white */
            margin: -0.5rem 0 0.5rem 0;
            letter-spacing: 2px; /* Slightly tighter for better readability */
            font-weight: 600; /* Bolder weight */
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.8), /* Dark shadow for contrast */
                         0 0 5px var(--accent); /* Subtle purple glow */
            font-family: 'Inter', sans-serif; /* Cleaner font */
        }

        .location {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--accent);
            background: rgba(0,0,0,0.6);
            padding: 6px 18px;
            border-radius: 50px; /* Smoother, pill-shaped edges */
            border: 1px solid rgba(168, 85, 247, 0.4); /* Softer border color */
            text-shadow: 0 0 5px var(--accent);
            backdrop-filter: blur(4px);
        }

        /* Glass Card Refined */
        .bio-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
            width: 320px;
            position: relative;
        }

        .avatar-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            object-fit: cover;
        }

        .status-dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 3px solid #1a1a1a;
            background: #747f8d; /* Offline */
        }

        .status-dot.online { background: #3ba55d; }
        .status-dot.idle { background: #faa81a; }
        .status-dot.dnd { background: #ed4245; }
        .status-dot.offline { background: #747f8d; }

        .username-row {
            display: flex;
            align-items: center;
            gap: 6px; /* Tighter gap */
            flex-wrap: wrap;
        }

        .username {
            font-weight: 600;
            font-size: 1rem;
            color: white;
            font-family: 'Syne', sans-serif;
            white-space: nowrap;
        }

        .user-tag {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
            font-weight: 400;
        }

        .clan-tag-container {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(255, 255, 255, 0.08);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: none; /* Shown via JS */
        }

        .clan-badge {
            width: 14px;
            height: 14px;
            border-radius: 2px;
        }

        .clan-tag-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            font-family: 'Inter', sans-serif;
        }

        .badges-container {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 6px;
        }

        .badge-icon {
            width: 18px;
            height: 18px;
            filter: drop-shadow(0 0 2px rgba(0,0,0,0.5));
        }

        .badge {
            font-size: 0.6rem;
            background: var(--accent);
            padding: 2px 6px;
            border-radius: 2px;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
        }

        .status-text {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .activity-display {
            font-size: 0.7rem;
            color: var(--accent);
            margin-top: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: none; /* Shown via JS if activity exists */
        }

        /* Socials */
        .socials {
            display: flex;
            gap: 1.2rem;
            background: rgba(0,0,0,0.5);
            padding: 10px 20px;
            border-radius: 4px;
            border: 1px solid var(--card-border);
        }

        .social-link {
            color: rgba(255,255,255,0.5);
            transition: all 0.2s;
        }

        .social-link:hover {
            color: var(--accent);
            transform: scale(1.2);
            filter: drop-shadow(0 0 5px var(--accent));
        }

        /* Music Player - Redesigned Floating Aesthetic */
        .player-pill {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 20px;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 12px 24px;
            border-radius: 100px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: fit-content;
            z-index: 100;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 20px rgba(168, 85, 247, 0.1);
            transition: opacity 1s ease, visibility 1s ease, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            opacity: 0;
            visibility: hidden;
        }

        .player-pill.visible {
            opacity: 1;
            visibility: visible;
        }

        .player-pill:hover {
            border-color: rgba(168, 85, 247, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6),
                        0 0 30px rgba(168, 85, 247, 0.2);
            transform: translateX(-50%) translateY(-5px);
        }

        .player-art-wrap {
            position: relative;
            width: 50px;
            height: 50px;
            flex-shrink: 0;
        }

        .player-art {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent);
            box-shadow: 0 0 15px var(--accent-glow);
            transition: transform 0.3s ease;
        }

        .player-pill:hover .player-art {
            transform: scale(1.1) rotate(5deg);
        }

        /* Pulse animation when playing */
        .is-playing .player-art {
            animation: art-pulse 2s infinite ease-in-out;
        }

        @keyframes art-pulse {
            0%, 100% { box-shadow: 0 0 15px var(--accent-glow); }
            50% { box-shadow: 0 0 25px var(--accent); }
        }

        .player-content {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 400px;
        }

        .track-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .track-name {
            font-size: 1.1rem;
            font-weight: 400;
            color: white;
            letter-spacing: 2px;
            font-family: 'Sedgwick Ave Display', cursive;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        .track-time {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.4);
            font-family: 'Inter', sans-serif;
            font-variant-numeric: tabular-nums;
        }

        .progress-container {
            width: 100%;
            height: 4px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        #audio-progress {
            height: 100%;
            background: linear-gradient(90deg, var(--accent), #ff4d79);
            width: 0%;
            border-radius: 10px;
            position: relative;
            box-shadow: 0 0 10px var(--accent);
        }

        .player-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-ctrl {
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.6);
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border-radius: 50%;
        }

        .btn-ctrl:hover {
            color: white;
            background: rgba(255,255,255,0.05);
            transform: scale(1.1);
        }

        .btn-play-ctrl {
            background: transparent;
            color: white;
            width: 40px;
            height: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: none;
        }

        .btn-play-ctrl:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--accent);
            box-shadow: 0 0 15px var(--accent-glow);
            transform: scale(1.15);
        }

        .btn-ctrl svg {
            width: 18px;
            height: 18px;
        }

        .btn-play-ctrl svg {
            width: 20px;
            height: 20px;
        }
        .volume-pill {
            position: fixed;
            top: 30px;
            left: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(26, 15, 15, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 10px;
            border-radius: 12px;
            gap: 0;
            width: 44px;
            transition: opacity 1s ease, visibility 1s ease, width 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), border-color 0.4s ease, box-shadow 0.4s ease;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
        }

        .volume-pill.visible {
            opacity: 1;
            visibility: visible;
        }

        .volume-pill:hover {
            width: 180px;
            border-color: rgba(168, 85, 247, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .vol-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            color: #a855f7;
            filter: drop-shadow(0 0 5px rgba(168, 85, 247, 0.4));
        }

        .vol-slider-container {
            width: 0;
            opacity: 0;
            margin-left: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .volume-pill:hover .vol-slider-container {
            width: 120px;
            margin-left: 12px;
            opacity: 1;
        }

        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 10px;
            background: rgba(255,255,255,0.05);
            outline: none;
            border-radius: 10px;
            overflow: hidden;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 1px;
            height: 10px;
            background: #a855f7;
            cursor: pointer;
            box-shadow: -200px 0 0 200px #a855f7;
            border-radius: 0;
        }

        input[type=range]::-moz-range-progress {
            background-color: #e6d5d5;
            height: 10px;
            border-radius: 10px 0 0 10px;
        }

        input[type=range]::-moz-range-thumb {
            width: 1px;
            height: 10px;
            background: #e6d5d5;
            border: none;
            border-radius: 0;
        }

        /* Progress Line */
        .track-progress-wrap {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: rgba(255,255,255,0.05);
        }

    </style>
</head>
<body>
    <div id="splash">
        <div class="enter-text">CLICK TO ENTER</div>
    </div>

    <div class="gif-bg"></div>
    <div class="overlay"></div>

    <audio id="bg-audio" loop>
        <source src="{{ asset('Vids/snaptik_7568500054340766984_v3.mp4') }}" type="video/mp4">
    </audio>

    <div class="volume-pill">
        <div class="vol-btn">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24" id="volume-icon">
                <path d="M3 9v6h4l5 4V5L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
            </svg>
        </div>
        <div class="vol-slider-container">
            <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="0.5">
        </div>
    </div>

    <div class="main-stack">
        <h1 class="title">KianToT</h1>
        <p class="subtitle">Isang gago lang po.</p>
        
        <div class="location">
            <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
            in your heart
        </div>

        <div class="bio-card">
            <div class="avatar-wrap">
                <img src="" id="discord-avatar" class="avatar" alt="Avatar">
                <div class="status-dot" id="discord-status-dot"></div>
            </div>
            <div class="user-info">
                <div class="username-row">
                    <span class="username" id="discord-global-name"></span>
                    <span class="user-tag" id="discord-tag"></span>
                    <div class="clan-tag-container" id="discord-clan-container">
                        <img src="" class="clan-badge" id="discord-clan-badge">
                        <span class="clan-tag-text" id="discord-clan-tag-text"></span>
                    </div>
                </div>
                <div class="badges-container" id="discord-badges">
                    <!-- Badges will be injected here -->
                </div>
                <div class="status-text" id="discord-status-text">last seen moments ago</div>
                <div class="activity-display" id="discord-activity"></div>
            </div>
        </div>

        <div class="socials">
            <a href="#" class="social-link" title="Discord">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 256 199">
                    <path d="M216.856,16.597 C200.285,8.843 182.566,3.208 164.042,0 C161.767,4.113 159.109,9.645 157.276,14.046 C137.584,11.085 118.073,11.085 98.743,14.046 C96.911,9.645 94.193,4.113 91.897,0 C73.353,3.208 55.613,8.864 39.042,16.638 C5.618,67.147 -3.443,116.401 1.087,164.956 C23.256,181.511 44.74,191.568 65.862,198.149 C71.077,190.971 75.728,183.341 79.735,175.3 C72.104,172.401 64.795,168.822 57.889,164.668 C59.721,163.311 61.513,161.891 63.245,160.431 C105.367,180.133 151.135,180.133 192.755,160.431 C194.506,161.891 196.298,163.311 198.11,164.668 C191.184,168.843 183.855,172.421 176.224,175.321 C180.23,183.341 184.862,190.992 190.097,198.169 C211.239,191.588 232.743,181.532 254.912,164.956 C260.228,108.668 245.831,59.866 216.856,16.597 Z M85.474,135.095 C72.829,135.095 62.459,123.29 62.459,108.915 C62.459,94.54 72.608,82.715 85.474,82.715 C98.341,82.715 108.71,94.519 108.489,108.915 C108.509,123.29 98.341,135.095 85.474,135.095 Z M170.525,135.095 C157.88,135.095 147.511,123.29 147.511,108.915 C147.511,94.54 157.659,82.715 170.525,82.715 C183.392,82.715 193.761,94.519 193.54,108.915 C193.54,123.29 183.392,135.095 170.525,135.095 Z"/>
                </svg>
            </a>
            <a href="https://www.instagram.com/kiancostaa/" target="_blank" rel="noopener noreferrer" class="social-link" title="Instagram">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2c2.717 0 3.056.01 4.122.058 1.064.048 1.79.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.637.417 1.363.465 2.427.048 1.066.058 1.405.058 4.122s-.01 3.056-.058 4.122c-.048 1.064-.218 1.79-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.637.247-1.363.417-2.427.465-1.066.048-1.405.058-4.122.058s-3.056-.01-4.122-.058c-1.064-.048-1.79-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.637-.417-1.363-.465-2.427-.047-1.024-.058-1.354-.058-4.077 0-2.722.01-3.051.058-4.077.048-1.064.218-1.79.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.523c.637-.247 1.363-.417 2.427-.465C8.944 2.01 9.283 2 12 2zm0 5a5 5 0 100 10 5 5 0 000-10zm0 8a3 3 0 110-6 3 3 0 010 6zm5.334-9.334a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z"/>
                </svg>
            </a>
            <a href="https://www.tiktok.com/@kian53" target="_blank" rel="noopener noreferrer" class="social-link" title="TikTok">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 448 512">
                    <path d="M448 209.91a210.06 210.06 0 0 1-122.77-39.25v178.72c0 101.71-82.44 184.14-184.14 184.14S-43.05 451.08-43.05 349.37 39.39 165.23 141.09 165.23c10.5 0 20.69 1.05 30.6 2.73v81.1c-10.05-4.11-20.91-6.3-32.33-6.3-43.95 0-79.67 35.72-79.67 79.67s35.72 79.67 79.67 79.67 79.67-35.72 79.67-79.67V0h81.1c0 46.85 38.01 84.86 84.86 84.86v81.1c-20.43 0-39.42-7.23-54.43-19.23v78.23l42.49 42.49c.86.86 1.71 1.73 2.56 2.61z"/>
                </svg>
            </a>
        </div>
    </div>

    <div class="player-pill" id="player-pill">
        <div class="player-art-wrap">
            <img src="{{ asset('PICS/image.png') }}" class="player-art" alt="Album Art">
        </div>
        <div class="player-content">
            <div class="track-info-row">
                <span class="track-name">Deamin</span>
                <div class="track-time-wrap">
                    <span class="track-time" id="current-time">0:00</span>
                    <span class="track-time" style="opacity: 0.3; margin: 0 4px;">/</span>
                    <span class="track-time" id="total-time">0:00</span>
                </div>
            </div>
            <div class="progress-container" id="progress-container">
                <div id="audio-progress"></div>
            </div>
        </div>
        <div class="player-actions">
            <button class="btn-ctrl" id="audio-prev" title="Reset">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg>
            </button>
            <button class="btn-ctrl btn-play-ctrl" id="audio-play-pause" title="Play/Pause">
                <svg viewBox="0 0 24 24" fill="currentColor" id="play-pause-icon">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>
            <button class="btn-ctrl" id="audio-next" title="Replay">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6z"/></svg>
            </button>
        </div>
    </div>
    <div id="custom-cursor"></div>
    <canvas id="cursor-canvas"></canvas>
</body>
</html>
