console.log("Cyber-Graffiti JS loaded");

document.addEventListener('DOMContentLoaded', () => {
    const splash = document.getElementById('splash');
    const audio = document.getElementById('bg-audio');
    const volumeIcon = document.getElementById('volume-icon');
    const volumeSlider = document.getElementById('volume-slider');
    const progressBar = document.getElementById('audio-progress');
    const progressContainer = document.getElementById('progress-container');
    const playPauseBtn = document.getElementById('audio-play-pause');
    const playPauseIcon = document.getElementById('play-pause-icon');
    const nextBtn = document.getElementById('audio-next');
    const prevBtn = document.getElementById('audio-prev');
    const currentTimeDisplay = document.getElementById('current-time');
    const totalTimeDisplay = document.getElementById('total-time');
    const playerPill = document.getElementById('player-pill');

    let savedVolume = 0.5;

    // Set initial volume
    if (audio && volumeSlider) {
        audio.volume = volumeSlider.value;
    }

    // Splash screen click handler
    if (splash) {
        splash.addEventListener('click', () => {
            splash.style.opacity = '0';
            splash.style.visibility = 'hidden';

            if (audio) {
                audio.play().then(() => {
                    updatePlayPauseIcon(true);

                    // Fade in all UI components
                    const uiElements = ['.main-stack', '.player-pill', '.volume-pill'];
                    uiElements.forEach(selector => {
                        const el = document.querySelector(selector);
                        if (el) el.classList.add('visible');
                    });
                }).catch(e => console.error("Audio play failed:", e));
            }
        });
    }

    // Play/Pause toggle
    if (playPauseBtn && audio) {
        playPauseBtn.addEventListener('click', () => {
            if (audio.paused) {
                audio.play();
                updatePlayPauseIcon(true);
            } else {
                audio.pause();
                updatePlayPauseIcon(false);
            }
        });
    }

    function updatePlayPauseIcon(isPlaying) {
        if (playPauseIcon) {
            if (isPlaying) {
                playPauseIcon.innerHTML = '<path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>'; // Pause
            } else {
                playPauseIcon.innerHTML = '<path d="M8 5v14l11-7z"/>'; // Play
            }
        }
        if (playerPill) {
            if (isPlaying) {
                playerPill.classList.add('is-playing');
            } else {
                playerPill.classList.remove('is-playing');
            }
        }
    }

    // Skip controls
    if (nextBtn && audio) {
        nextBtn.addEventListener('click', () => {
            audio.currentTime = 0;
            audio.play();
            updatePlayPauseIcon(true);
        });
    }

    if (prevBtn && audio) {
        prevBtn.addEventListener('click', () => {
            audio.currentTime = 0;
            audio.play();
            updatePlayPauseIcon(true);
        });
    }

    // Volume Slider
    if (volumeSlider && audio) {
        volumeSlider.addEventListener('input', (e) => {
            const val = e.target.value;
            audio.volume = val;
            savedVolume = val;
            updateVolumeIcon(val);
        });
    }

    function updateVolumeIcon(volume) {
        if (!volumeIcon) return;
        if (volume == 0) {
            volumeIcon.innerHTML = '<path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 4v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 5L9.91 7.09 12 9.18V5z"/>';
        } else {
            volumeIcon.innerHTML = '<path d="M3 9v6h4l5 4V5L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>';
        }
    }

    // Progress & Time
    if (audio && progressBar && progressContainer) {
        audio.addEventListener('loadedmetadata', () => {
            if (totalTimeDisplay && !isNaN(audio.duration)) {
                totalTimeDisplay.textContent = formatTime(audio.duration);
            }
        });

        audio.addEventListener('timeupdate', () => {
            if (!isNaN(audio.duration) && audio.duration > 0) {
                const percent = (audio.currentTime / audio.duration) * 100;
                progressBar.style.width = percent + '%';
            }

            if (currentTimeDisplay) {
                currentTimeDisplay.textContent = formatTime(audio.currentTime);
            }

            if (totalTimeDisplay && !isNaN(audio.duration) && totalTimeDisplay.textContent === '0:00') {
                totalTimeDisplay.textContent = formatTime(audio.duration);
            }
        });

        progressContainer.addEventListener('click', (e) => {
            if (isNaN(audio.duration)) return;
            const rect = progressContainer.getBoundingClientRect();
            const pos = (e.clientX - rect.left) / rect.width;
            audio.currentTime = pos * audio.duration;
        });
    }

    function formatTime(seconds) {
        if (isNaN(seconds) || seconds === Infinity || seconds < 0) return "0:00";
        const min = Math.floor(seconds / 60);
        const sec = Math.floor(seconds % 60);
        return `${min}:${sec < 10 ? '0' : ''}${sec}`;
    }

    // Discord Presence Integration
    const discordAvatar = document.getElementById('discord-avatar');
    const discordStatusDot = document.getElementById('discord-status-dot');
    const discordStatusText = document.getElementById('discord-status-text');
    const discordActivity = document.getElementById('discord-activity');
    const discordGlobalName = document.getElementById('discord-global-name');
    const discordTag = document.getElementById('discord-tag');
    const discordBadges = document.getElementById('discord-badges');
    const discordClanContainer = document.getElementById('discord-clan-container');
    const discordClanBadge = document.getElementById('discord-clan-badge');
    const discordClanTagText = document.getElementById('discord-clan-tag-text');

    const BADGE_MAPPING = {
        1: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/discordstaff.svg",
        2: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/discordpartner.svg",
        4: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/hypesquadevents.svg",
        8: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/discordbughunter1.svg",
        64: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/hypesquadbravery.svg",
        128: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/hypesquadbrilliance.svg",
        256: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/hypesquadbalance.svg",
        512: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/discordearlysupporter.svg",
        16384: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/discordbughunter2.svg",
        131072: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/discordbotdev.svg",
        4194304: "https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/activedeveloper.svg"
    };

    async function updateDiscordPresence() {
        try {
            // Dual-Fetch for maximum accuracy
            const [lanyardRes, profileRes] = await Promise.all([
                fetch('https://api.lanyard.rest/v1/users/855694452315652096').then(r => r.json()),
                fetch('https://dcdn.dstn.to/profile/855694452315652096').then(r => r.json())
            ]);

            const lanyard = lanyardRes.data;
            const profile = profileRes.user;
            const profileBadges = profileRes.badges;

            if (!lanyard || !profile) return;

            // 1. Update Status Dot (from Lanyard - Live)
            if (discordStatusDot) {
                discordStatusDot.className = 'status-dot ' + lanyard.discord_status;
            }

            // 2. Update Status Text (from Lanyard)
            if (discordStatusText) {
                if (lanyard.discord_status === 'offline') {
                    discordStatusText.textContent = 'Last seen moments ago';
                } else {
                    discordStatusText.textContent = lanyard.discord_status.toUpperCase();
                }
            }

            // 3. Update Names (from Profile API - Stable)
            if (discordGlobalName) discordGlobalName.textContent = profile.global_name || profile.username;
            if (discordTag) discordTag.textContent = `@${profile.username}`;

            // 4. Update Clan Tag (from Profile API)
            if (discordClanContainer && profile.clan) {
                discordClanContainer.style.display = 'inline-flex';
                if (discordClanBadge) discordClanBadge.src = `https://cdn.discordapp.com/clan-badges/${profile.clan.identity_guild_id}/${profile.clan.badge}.png`;
                if (discordClanTagText) discordClanTagText.textContent = profile.clan.tag;
            } else {
                discordClanContainer.style.display = 'none';
            }

            // 5. Update Avatar
            if (discordAvatar && profile.avatar) {
                discordAvatar.src = `https://cdn.discordapp.com/avatars/${profile.id}/${profile.avatar}.png`;
            }

            // 6. Update Badges (from Profile API - Comprehensive)
            if (discordBadges && profileBadges) {
                let badgeHtml = '';
                profileBadges.forEach(badge => {
                    const iconUrl = `https://cdn.discordapp.com/badge-icons/${badge.icon}.png`;
                    badgeHtml += `<img src="${iconUrl}" class="badge-icon" alt="${badge.description}" title="${badge.description}">`;
                });

                // Nitro Check (if premium_type > 0 in profile data)
                if (profileRes.premium_type > 0) {
                    badgeHtml += `<img src="https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/discordnitro.svg" class="badge-icon" alt="Nitro" title="Nitro Subscriber">`;
                }

                discordBadges.innerHTML = badgeHtml;
            }

            // 7. Update Activity (from Lanyard - Live)
            if (discordActivity) {
                const activity = lanyard.activities.find(a => a.type === 0) || lanyard.activities[0];
                if (lanyard.listening_to_spotify && lanyard.spotify) {
                    discordActivity.style.display = 'block';
                    discordActivity.textContent = `Listening to Spotify`;
                } else if (activity) {
                    discordActivity.style.display = 'block';
                    discordActivity.textContent = activity.state ? `${activity.name}: ${activity.state}` : `Playing ${activity.name}`;
                } else {
                    discordActivity.style.display = 'none';
                }
            }
        } catch (error) {
            console.error("Discord Integration Error:", error);
        }
    }

    updateDiscordPresence();
    setInterval(updateDiscordPresence, 15000);

    // --- Custom Cursor & Ocean Wave Trail ---
    const cursor = document.getElementById('custom-cursor');
    const canvas = document.getElementById('cursor-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    let mouseX = 0;
    let mouseY = 0;
    let cursorX = 0;
    let cursorY = 0;

    const points = [];
    const pointCount = 25; // Length of the trail
    const waveAmplitude = 8;
    const waveFrequency = 0.08;
    let time = 0;

    for (let i = 0; i < pointCount; i++) {
        points.push({ x: 0, y: 0 });
    }

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Initial position to avoid teleporting from (0,0)
    mouseX = window.innerWidth / 2;
    mouseY = window.innerHeight / 2;
    cursorX = mouseX;
    cursorY = mouseY;
    points.forEach(p => { p.x = mouseX; p.y = mouseY; });

    // Handle clicks for cursor effect
    document.addEventListener('mousedown', () => {
        if (cursor) {
            cursor.style.width = '12px';
            cursor.style.height = '12px';
        }
    });
    document.addEventListener('mouseup', () => {
        if (cursor) {
            cursor.style.width = '8px';
            cursor.style.height = '8px';
        }
    });

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Smooth cursor follow
        cursorX += (mouseX - cursorX) * 0.25;
        cursorY += (mouseY - cursorY) * 0.25;

        if (cursor) {
            cursor.style.left = cursorX + 'px';
            cursor.style.top = cursorY + 'px';
        }

        // Update trail points
        points[0].x = cursorX;
        points[0].y = cursorY;

        for (let i = 1; i < pointCount; i++) {
            const p = points[i];
            const prevP = points[i - 1];
            p.x += (prevP.x - p.x) * 0.35;
            p.y += (prevP.y - p.y) * 0.35;
        }

        // Draw the wave trail
        const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#a855f7';

        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';

        time += waveFrequency;

        for (let i = 1; i < pointCount; i++) {
            const p = points[i];
            const prevP = points[i - 1];

            // Add wave oscillation based on index and time
            const waveOffset = Math.sin(time + i * 0.4) * waveAmplitude * (i / pointCount);
            const prevWaveOffset = Math.sin(time + (i - 1) * 0.4) * waveAmplitude * ((i - 1) / pointCount);

            ctx.beginPath();
            ctx.strokeStyle = accentColor;
            ctx.lineWidth = 3 * (1 - i / pointCount); // Taper the line
            ctx.globalAlpha = 0.6 * (1 - i / pointCount); // Fade out

            ctx.moveTo(prevP.x, prevP.y + prevWaveOffset);
            ctx.lineTo(p.x, p.y + waveOffset);
            ctx.stroke();
        }

        requestAnimationFrame(animate);
    }

    animate();
});
