console.log("landing.js: Script started");

document.addEventListener('DOMContentLoaded', () => {
    console.log("landing.js: DOMContentLoaded fired");
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

    // Indicate JS is ready
    document.body.classList.add('js-ready');

    // Set initial volume
    if (audio && volumeSlider) {
        audio.volume = volumeSlider.value;
    }

    // Splash screen click handler
    if (splash) {
        splash.addEventListener('click', () => {
            splash.style.opacity = '0';
            splash.style.pointerEvents = 'none';
            setTimeout(() => {
                splash.style.visibility = 'hidden';
            }, 1000);

            // Open all UI components immediately
            const uiElements = ['.main-stack', '.player-pill', '.volume-pill'];
            uiElements.forEach(selector => {
                const el = document.querySelector(selector);
                if (el) el.classList.add('visible');
            });

            if (audio) {
                audio.play().then(() => {
                    updatePlayPauseIcon(true);
                }).catch(e => {
                    console.error("Audio play failed:", e);
                    // Still show UI even if audio fails
                    updatePlayPauseIcon(false);
                });
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
    const USERS_TO_MONITOR = [
        {
            id: '855694452315652096', // Kian
            fallbackName: 'KianToT',
            fallbackUsername: 'kiantot',
            elements: {
                avatar: 'discord-avatar',
                statusDot: 'discord-status-dot',
                statusText: 'discord-status-text',
                activity: 'discord-activity',
                globalName: 'discord-global-name',
                tag: 'discord-tag',
                badges: 'discord-badges',
                clanContainer: 'discord-clan-container',
                clanBadge: 'discord-clan-badge',
                clanTagText: 'discord-clan-tag-text'
            }
        },
        {
            id: '992264873453027350', // Bro 1
            fallbackName: '₱',
            fallbackUsername: '116.65.36.216',
            elements: {
                avatar: 'bro1-avatar',
                statusDot: 'bro1-status-dot',
                statusText: 'bro1-status-text',
                activity: 'bro1-activity',
                globalName: 'bro1-global-name',
                tag: 'bro1-tag',
                badges: 'bro1-badges',
                clanContainer: 'bro1-clan-container',
                clanBadge: 'bro1-clan-badge',
                clanTagText: 'bro1-clan-tag-text'
            }
        },
        {
            id: '687983215880699905', // Bro 2
            fallbackName: 'Bro 2',
            fallbackUsername: '687983215880699905',
            elements: {
                avatar: 'bro2-avatar',
                statusDot: 'bro2-status-dot',
                statusText: 'bro2-status-text',
                activity: 'bro2-activity',
                globalName: 'bro2-global-name',
                tag: 'bro2-tag',
                badges: 'bro2-badges',
                clanContainer: 'bro2-clan-container',
                clanBadge: 'bro2-clan-badge',
                clanTagText: 'bro2-clan-tag-text'
            }
        }
    ];

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

    async function fetchDiscordData(userId, config, fallbackName, fallbackUsername) {
        try {
            const [lanyardRes, profileRes, japiRes] = await Promise.allSettled([
                fetch(`https://api.lanyard.rest/v1/users/${userId}`).then(r => {
                    if (!r.ok) throw new Error(`Lanyard returned ${r.status}`);
                    return r.json();
                }),
                fetch(`https://dcdn.dstn.to/profile/${userId}`).then(r => {
                    if (!r.ok) throw new Error(`dcdn returned ${r.status}`);
                    return r.json();
                }),
                fetch(`https://japi.rest/discord/v1/user/${userId}`).then(r => {
                    if (!r.ok) throw new Error(`japi returned ${r.status}`);
                    return r.json();
                })
            ]);

            const lanyard = lanyardRes.status === 'fulfilled' && lanyardRes.value && lanyardRes.value.success ? lanyardRes.value.data : null;
            const profileData = profileRes.status === 'fulfilled' ? profileRes.value : null;
            const profile = profileData && profileData.user ? profileData.user : null;
            const profileBadges = profileData && profileData.badges ? profileData.badges : null;
            const profilePremium = profileData && profileData.premium_type ? profileData.premium_type : 0;

            // japi.rest fallback data
            const japiData = japiRes.status === 'fulfilled' && japiRes.value && japiRes.value.data ? japiRes.value.data : null;
            const japiFlags = japiData && japiData.public_flags_array ? japiData.public_flags_array : [];
            const japiHasNitro = japiFlags.includes('NITRO');

            // Elements
            const avatarEl = document.getElementById(config.avatar);
            const statusDotEl = document.getElementById(config.statusDot);
            const statusTextEl = document.getElementById(config.statusText);
            const activityEl = document.getElementById(config.activity);
            const nameEl = document.getElementById(config.globalName);
            const tagEl = document.getElementById(config.tag);
            const badgesEl = document.getElementById(config.badges);
            const clanContainerEl = document.getElementById(config.clanContainer);
            const clanBadgeEl = document.getElementById(config.clanBadge);
            const clanTagTextEl = document.getElementById(config.clanTagText);

            // 1. Status Dot & Text (Lanyard dependent)
            let status = 'offline';
            if (lanyard && lanyard.discord_status) {
                status = lanyard.discord_status;
            }

            if (statusDotEl) statusDotEl.className = 'status-dot ' + status;

            if (statusTextEl) {
                if (status === 'offline') {
                    statusTextEl.textContent = 'Last seen moments ago';
                } else {
                    statusTextEl.textContent = status.toUpperCase();
                }
            }

            // 2. Names (Profile > Lanyard > japi.rest > fallback)
            if (profile) {
                if (nameEl) nameEl.textContent = profile.global_name || profile.username;
                if (tagEl) tagEl.textContent = `@${profile.username}`;
            } else if (lanyard && lanyard.discord_user) {
                if (nameEl) nameEl.textContent = lanyard.discord_user.display_name || lanyard.discord_user.global_name || lanyard.discord_user.username;
                if (tagEl) tagEl.textContent = `@${lanyard.discord_user.username}`;
            } else if (japiData) {
                if (nameEl) nameEl.textContent = japiData.global_name || japiData.username;
                if (tagEl) tagEl.textContent = `@${japiData.username}`;
            } else if (nameEl && (!nameEl.textContent || nameEl.textContent === 'Loading...')) {
                nameEl.textContent = fallbackName || 'Unknown User';
                if (tagEl) tagEl.textContent = fallbackUsername ? `@${fallbackUsername}` : '';
            }

            // 4. Clan Tag
            if (clanContainerEl) {
                const clanData = (profile && profile.clan) || (lanyard && lanyard.discord_user && lanyard.discord_user.primary_guild) || (japiData && (japiData.clan || japiData.primary_guild));
                if (clanData && clanData.tag) {
                    clanContainerEl.style.display = 'inline-flex';
                    if (clanBadgeEl && clanData.badge && clanData.identity_guild_id) {
                        clanBadgeEl.src = `https://cdn.discordapp.com/clan-badges/${clanData.identity_guild_id}/${clanData.badge}.png`;
                    }
                    if (clanTagTextEl) clanTagTextEl.textContent = clanData.tag;
                } else {
                    clanContainerEl.style.display = 'none';
                }
            }

            // 5. Avatar (Profile > Lanyard > japi.rest > default)
            if (avatarEl) {
                if (profile && profile.avatar) {
                    avatarEl.src = `https://cdn.discordapp.com/avatars/${profile.id}/${profile.avatar}.png`;
                } else if (lanyard && lanyard.discord_user && lanyard.discord_user.avatar) {
                    avatarEl.src = `https://cdn.discordapp.com/avatars/${lanyard.discord_user.id}/${lanyard.discord_user.avatar}.png`;
                } else if (japiData && japiData.avatar) {
                    avatarEl.src = `https://cdn.discordapp.com/avatars/${japiData.id}/${japiData.avatar}.png`;
                } else if (!avatarEl.getAttribute('data-loaded')) {
                    avatarEl.src = `https://cdn.discordapp.com/embed/avatars/${parseInt(userId.slice(-1)) % 5}.png`;
                }
                avatarEl.setAttribute('data-loaded', 'true');
            }

            // 6. Badges
            if (badgesEl) {
                let badgeHtml = '';
                if (profileBadges && Array.isArray(profileBadges)) {
                    profileBadges.forEach(badge => {
                        const iconUrl = `https://cdn.discordapp.com/badge-icons/${badge.icon}.png`;
                        badgeHtml += `<img src="${iconUrl}" class="badge-icon" alt="${badge.description}" title="${badge.description}">`;
                    });
                }
                if (profilePremium > 0 || japiHasNitro) {
                    badgeHtml += `<img src="https://cdn.jsdelivr.net/gh/mezotv/discord-badges@main/assets/discordnitro.svg" class="badge-icon" alt="Nitro" title="Nitro Subscriber">`;
                }
                badgesEl.innerHTML = badgeHtml;
            }

            // 7. Activity
            if (activityEl) {
                if (lanyard && lanyard.activities) {
                    const activity = lanyard.activities.find(a => a.type === 0) || lanyard.activities[0];
                    if (lanyard.listening_to_spotify && lanyard.spotify) {
                        activityEl.style.display = 'block';
                        activityEl.textContent = `Listening to Spotify`;
                    } else if (activity) {
                        activityEl.style.display = 'block';
                        activityEl.textContent = activity.state ? `${activity.name}: ${activity.state}` : `Playing ${activity.name}`;
                    } else {
                        activityEl.style.display = 'none';
                    }
                } else {
                    activityEl.style.display = 'none';
                }
            }

        } catch (error) {
            console.error(`Error fetching data for ${userId}:`, error);
        }
    }

    function updateAllPresence() {
        USERS_TO_MONITOR.forEach(user => {
            fetchDiscordData(user.id, user.elements, user.fallbackName, user.fallbackUsername);
        });
    }

    updateAllPresence();
    setInterval(updateAllPresence, 15000);

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
