document.addEventListener('DOMContentLoaded', () => {
    const nav = document.querySelector('[data-nav]');
    const toggle = document.querySelector('[data-nav-toggle]');

    if (nav && toggle) {
        toggle.addEventListener('click', () => {
            nav.classList.toggle('is-open');
        });

        nav.querySelectorAll('a[href*="#"]').forEach((link) => {
            link.addEventListener('click', () => {
                nav.classList.remove('is-open');
            });
        });
    }

    const adminBody = document.querySelector('[data-admin-body]');
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');

    if (adminBody && sidebarToggle) {
        const desktopSidebar = window.matchMedia('(min-width: 1101px)');

        const applySidebarState = (collapsed) => {
            const shouldCollapse = desktopSidebar.matches && collapsed;
            adminBody.classList.toggle('admin-sidebar-collapsed', shouldCollapse);
            document.documentElement.classList.toggle('admin-sidebar-collapsed-preload', shouldCollapse);
            sidebarToggle.setAttribute('aria-expanded', shouldCollapse ? 'false' : 'true');
        };

        const storedSidebarState = () => {
            try {
                return window.localStorage.getItem('adminSidebarCollapsed') === 'true';
            } catch (error) {
                return false;
            }
        };

        try {
            applySidebarState(storedSidebarState());
        } catch (error) {
            applySidebarState(false);
        }

        sidebarToggle.addEventListener('click', () => {
            if (!desktopSidebar.matches) {
                applySidebarState(false);
                return;
            }

            const collapsed = !adminBody.classList.contains('admin-sidebar-collapsed');
            applySidebarState(collapsed);

            try {
                window.localStorage.setItem('adminSidebarCollapsed', String(collapsed));
            } catch (error) {
                // Ignore storage failures; the toggle should still work for this page.
            }
        });

        desktopSidebar.addEventListener('change', () => {
            applySidebarState(storedSidebarState());
        });
    }

    const sectionLinks = Array.from(document.querySelectorAll('[data-section-link]'));

    if (sectionLinks.length) {
        const setActiveSection = (id) => {
            sectionLinks.forEach((link) => {
                link.classList.toggle('active', link.dataset.sectionLink === id);
            });
        };

        const hash = window.location.hash.replace('#', '');

        if (hash) {
            setActiveSection(hash);
        }

        if ('IntersectionObserver' in window) {
            const sections = sectionLinks
                .map((link) => document.getElementById(link.dataset.sectionLink))
                .filter(Boolean);

            const navObserver = new IntersectionObserver((entries) => {
                entries
                    .filter((entry) => entry.isIntersecting)
                    .sort((a, b) => b.intersectionRatio - a.intersectionRatio)
                    .slice(0, 1)
                    .forEach((entry) => setActiveSection(entry.target.id));
            }, {
                rootMargin: '-28% 0px -56% 0px',
                threshold: [0.08, 0.18, 0.32],
            });

            sections.forEach((section) => navObserver.observe(section));
        }
    }

    document.querySelectorAll('[data-toast-close]').forEach((button) => {
        button.addEventListener('click', () => {
            const toast = button.closest('.toast');

            if (!toast) {
                return;
            }

            toast.classList.add('is-hiding');
            setTimeout(() => toast.remove(), 180);
        });
    });

    document.querySelectorAll('.toast').forEach((toast) => {
        setTimeout(() => {
            if (!toast.isConnected) {
                return;
            }

            toast.classList.add('is-hiding');
            setTimeout(() => toast.remove(), 180);
        }, 5000);
    });

    const articleContent = document.querySelector('.article-content');

    if (articleContent) {
        articleContent.querySelectorAll('a[href]').forEach((link) => {
            const rel = new Set((link.getAttribute('rel') || '').split(/\s+/).filter(Boolean));

            rel.add('noopener');
            rel.add('noreferrer');
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', Array.from(rel).join(' '));
        });

        articleContent.querySelectorAll('pre').forEach((pre) => {
            let code = pre.querySelector('code');

            if (!code) {
                code = document.createElement('code');
                code.textContent = pre.textContent || '';
                pre.textContent = '';
                pre.appendChild(code);
            }

            const classNames = [
                ...Array.from(pre.classList),
                ...Array.from(code.classList),
            ];

            if (classNames.some((className) => className.startsWith('language-'))) {
                return;
            }

            const text = (code || pre).textContent || '';
            const language = /(<\?php|\$[a-zA-Z_]|@(?:if|else|endif|foreach|endphp)|->)/.test(text)
                ? 'php'
                : /<\/?[a-z][\s\S]*>/i.test(text)
                    ? 'markup'
                    : /\b(const|let|var|function|return|import|export)\b|=>/.test(text)
                        ? 'javascript'
                        : 'markup';

            pre.classList.add(`language-${language}`);

            code.classList.add(`language-${language}`);
        });

        if (window.Prism && typeof window.Prism.highlightAllUnder === 'function') {
            window.Prism.highlightAllUnder(articleContent);
        }
    }

    const tinyEditors = document.querySelectorAll('[data-tinymce-editor]');

    if (tinyEditors.length && window.tinymce) {
        const getSelectedImage = (editor) => {
            const node = editor.selection.getNode();

            if (!node) {
                return null;
            }

            if (node.nodeName === 'IMG') {
                return node;
            }

            if (node.querySelector) {
                const image = node.querySelector('img');

                if (image) {
                    return image;
                }
            }

            const figure = editor.dom.getParent(node, 'figure');

            return figure ? figure.querySelector('img') : null;
        };

        const applyImageCrop = (editor, ratio) => {
            const image = getSelectedImage(editor);

            if (!image) {
                editor.windowManager.alert('Select an image in the editor first.');
                return;
            }

            if (!ratio) {
                editor.dom.setStyle(image, 'aspect-ratio', null);
                editor.dom.setStyle(image, 'object-fit', null);
                editor.dom.setStyle(image, 'object-position', null);
                editor.dom.setStyle(image, 'width', null);
                return;
            }

            editor.dom.setStyles(image, {
                width: '100%',
                aspectRatio: ratio,
                objectFit: 'cover',
                objectPosition: '50% 50%',
            });
        };

        window.tinymce.init({
            selector: '[data-tinymce-editor]',
            height: 560,
            branding: false,
            promotion: false,
            menubar: 'edit insert view format table tools',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table codesample help wordcount autoresize',
            toolbar: [
                'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify',
                'bullist numlist outdent indent | link image media table codesample blockquote | imagecrop | removeformat code fullscreen',
            ].join(' | '),
            block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Quote=blockquote; Code=pre',
            contextmenu: 'link image table',
            object_resizing: 'img,table',
            image_title: true,
            image_caption: true,
            image_advtab: true,
            image_dimensions: true,
            paste_data_images: false,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            valid_elements: '*[*]',
            extended_valid_elements: 'figure[class|style],figcaption[class|style],img[src|alt|title|width|height|style|class|loading],pre[class|style],code[class|style]',
            content_style: [
                'body { font-family: Inter, Arial, sans-serif; color: #303832; line-height: 1.65; }',
                'img { max-width: 100%; height: auto; border-radius: 8px; }',
                'figure { margin: 18px 0; }',
                'figcaption { margin-top: 6px; color: #66716c; font-size: 0.9rem; }',
                'pre { overflow-x: auto; padding: 14px; border-radius: 8px; color: #dbeafe; background: #121614; }',
                'code { font-family: Consolas, Monaco, monospace; }',
                'blockquote { border-left: 4px solid #0f766e; margin: 18px 0; padding: 12px 16px; background: #eef7f4; }',
            ].join(' '),
            setup: (editor) => {
                editor.ui.registry.addMenuButton('imagecrop', {
                    text: 'Crop',
                    fetch: (callback) => {
                        callback([
                            { type: 'menuitem', text: 'Natural image', onAction: () => applyImageCrop(editor, '') },
                            { type: 'menuitem', text: 'Wide 16:9', onAction: () => applyImageCrop(editor, '16 / 9') },
                            { type: 'menuitem', text: 'Classic 4:3', onAction: () => applyImageCrop(editor, '4 / 3') },
                            { type: 'menuitem', text: 'Square 1:1', onAction: () => applyImageCrop(editor, '1 / 1') },
                            { type: 'menuitem', text: 'Portrait 3:4', onAction: () => applyImageCrop(editor, '3 / 4') },
                        ]);
                    },
                });

                editor.on('change keyup undo redo', () => {
                    editor.save();
                });
            },
        });

        document.querySelectorAll('form').forEach((form) => {
            form.addEventListener('submit', () => {
                window.tinymce.triggerSave();
            });
        });
    }

    document.querySelectorAll('[data-cover-input]').forEach((input) => {
        const container = input.closest('.cover-uploader, .avatar-uploader');
        const preview = container && container.querySelector('[data-cover-preview]');
        const placeholder = container && container.querySelector('[data-cover-placeholder]');

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];

            if (!file || !preview) {
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.hidden = false;

            if (placeholder) {
                placeholder.hidden = true;
            }
        });
    });

    const animateCount = (element) => {
        const target = Number(element.dataset.countUp || 0);
        const mode = element.dataset.countMode || 'default';

        if (!Number.isFinite(target)) {
            return;
        }

        const duration = mode === 'stat' ? 2350 : 1850;
        const start = performance.now();

        const tick = (time) => {
            const progress = Math.min((time - start) / duration, 1);
            const overshoot = 1.08;
            const eased = progress < .88
                ? (1 - Math.pow(1 - progress / .88, 3)) * overshoot
                : overshoot - ((progress - .88) / .12) * (overshoot - 1);

            if (mode === 'stat' && target > 0 && target <= 12 && progress < .64) {
                element.textContent = String(Math.floor(progress * 34) % (Math.max(target, 9) + 1));
            } else {
                element.textContent = String(Math.min(target, Math.round(target * eased)));
            }

            if (progress < 1) {
                window.requestAnimationFrame(tick);
            }
        };

        window.requestAnimationFrame(tick);
    };

    const countItems = document.querySelectorAll('[data-count-up]');

    if (countItems.length) {
        if ('IntersectionObserver' in window) {
            const countObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting || entry.target.dataset.countDone) {
                        return;
                    }

                    entry.target.dataset.countDone = 'true';
                    animateCount(entry.target);
                    countObserver.unobserve(entry.target);
                });
            }, { threshold: 0.3 });

            countItems.forEach((item) => countObserver.observe(item));
        } else {
            countItems.forEach(animateCount);
        }
    }

    const skillBars = document.querySelectorAll('[data-skill-bar]');

    if (skillBars.length) {
        if ('IntersectionObserver' in window) {
            const skillObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');
                    skillObserver.unobserve(entry.target);
                });
            }, { threshold: 0.35 });

            skillBars.forEach((bar) => skillObserver.observe(bar));
        } else {
            skillBars.forEach((bar) => bar.classList.add('is-visible'));
        }
    }

    document.querySelectorAll('[data-testimonial-carousel]').forEach((carousel) => {
        const section = carousel.closest('.testimonial-section');
        const track = carousel.querySelector('[data-testimonial-track]');
        const previous = section && section.querySelector('[data-testimonial-prev]');
        const next = section && section.querySelector('[data-testimonial-next]');

        if (!track) {
            return;
        }

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let offset = 0;
        let halfWidth = 0;
        let previousTime = performance.now();
        let paused = false;
        let manualPauseUntil = 0;

        const measure = () => {
            const trackStyle = window.getComputedStyle(track);
            const gap = Number.parseFloat(trackStyle.columnGap || trackStyle.gap || '0') || 0;
            halfWidth = (track.scrollWidth + gap) / 2;
            offset = -halfWidth;
            track.style.transform = `translate3d(${offset}px, 0, 0)`;
        };

        const step = (time) => {
            const delta = time - previousTime;
            previousTime = time;

            if (!reduceMotion && !paused && time > manualPauseUntil && halfWidth > 0) {
                offset += delta * 0.035;

                if (offset >= 0) {
                    offset = -halfWidth;
                }

                track.style.transform = `translate3d(${offset}px, 0, 0)`;
            }

            window.requestAnimationFrame(step);
        };

        const move = (direction) => {
            if (!halfWidth) {
                return;
            }

            manualPauseUntil = performance.now() + 1200;
            offset += direction * 280;

            if (offset >= 0) {
                offset = -halfWidth + (offset % halfWidth);
            }

            if (offset <= -halfWidth) {
                offset = offset + halfWidth;
            }

            track.style.transition = 'transform .35s ease';
            track.style.transform = `translate3d(${offset}px, 0, 0)`;
            setTimeout(() => {
                track.style.transition = '';
            }, 360);
        };

        carousel.addEventListener('mouseenter', () => {
            paused = true;
        });

        carousel.addEventListener('mouseleave', () => {
            paused = false;
            previousTime = performance.now();
        });

        if (previous) {
            previous.addEventListener('click', () => move(-1));
        }

        if (next) {
            next.addEventListener('click', () => move(1));
        }

        window.addEventListener('resize', measure);
        measure();

        if (!reduceMotion) {
            window.requestAnimationFrame(step);
        }
    });

    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && 'IntersectionObserver' in window) {
        const revealItems = document.querySelectorAll([
            '.section',
            '.project-card',
            '.service-card',
            '.skill-group',
            '.timeline article',
            '.testimonial-card',
            '.blog-card',
            '.section-visual',
            '.newsletter-panel',
        ].join(','));

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        revealItems.forEach((item) => {
            item.classList.add('reveal-ready');
            observer.observe(item);
        });
    }

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-color-picker]').forEach((input) => {
        const value = input.closest('.color-control')?.querySelector('[data-color-value]');

        if (!value) {
            return;
        }

        const updateValue = () => {
            const hex = input.value.replace('#', '');

            if (!/^[0-9a-fA-F]{6}$/.test(hex)) {
                value.textContent = '';
                return;
            }

            const red = Number.parseInt(hex.slice(0, 2), 16);
            const green = Number.parseInt(hex.slice(2, 4), 16);
            const blue = Number.parseInt(hex.slice(4, 6), 16);

            value.textContent = `rgb(${red}, ${green}, ${blue})`;
        };

        input.addEventListener('input', updateValue);
        updateValue();
    });

    document.querySelectorAll('[data-sortable-home-sections]').forEach((list) => {
        const getDragAfterElement = (y) => {
            const items = [...list.querySelectorAll('[data-sortable-item]:not(.is-dragging)')];

            return items.reduce((closest, item) => {
                const box = item.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;

                if (offset < 0 && offset > closest.offset) {
                    return { offset, item };
                }

                return closest;
            }, { offset: Number.NEGATIVE_INFINITY, item: null }).item;
        };

        list.querySelectorAll('[data-sortable-item]').forEach((item) => {
            item.addEventListener('dragstart', (event) => {
                item.classList.add('is-dragging');

                if (event.dataTransfer) {
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', item.querySelector('input')?.value || '');
                }
            });

            item.addEventListener('dragend', () => {
                item.classList.remove('is-dragging');
                list.querySelectorAll('.is-drag-over').forEach((element) => element.classList.remove('is-drag-over'));
            });
        });

        list.addEventListener('dragover', (event) => {
            event.preventDefault();
            const dragging = list.querySelector('.is-dragging');

            if (!dragging) {
                return;
            }

            const afterElement = getDragAfterElement(event.clientY);

            list.querySelectorAll('.is-drag-over').forEach((element) => element.classList.remove('is-drag-over'));

            if (afterElement) {
                afterElement.classList.add('is-drag-over');
                list.insertBefore(dragging, afterElement);
            } else {
                list.appendChild(dragging);
            }
        });

        list.addEventListener('drop', () => {
            list.querySelectorAll('.is-drag-over').forEach((element) => element.classList.remove('is-drag-over'));
        });
    });

    document.querySelectorAll('[data-portrait-editor]').forEach((editor) => {
        const input = editor.querySelector('[data-portrait-input]');
        const preview = editor.querySelector('[data-portrait-preview]');
        const placeholder = editor.querySelector('[data-portrait-placeholder]');
        const cropInput = editor.querySelector('[data-portrait-crop]');
        const workbench = editor.querySelector('[data-crop-workbench]');
        const canvas = editor.querySelector('[data-crop-canvas]');
        const zoom = editor.querySelector('[data-crop-zoom]');
        const rotate = editor.querySelector('[data-crop-rotate]');
        const reset = editor.querySelector('[data-crop-reset]');

        if (!input || !canvas || !cropInput || !workbench) {
            return;
        }

        const context = canvas.getContext('2d');
        const state = {
            image: null,
            offsetX: 0,
            offsetY: 0,
            zoom: 1,
            rotate: 0,
            dragging: false,
            lastX: 0,
            lastY: 0,
        };

        const draw = () => {
            if (!state.image) {
                return;
            }

            const size = canvas.width;
            const baseScale = Math.max(size / state.image.width, size / state.image.height) * 1.08;
            const scale = baseScale * state.zoom;

            context.clearRect(0, 0, size, size);
            context.fillStyle = '#f7f8f4';
            context.fillRect(0, 0, size, size);
            context.save();
            context.translate(size / 2 + state.offsetX, size / 2 + state.offsetY);
            context.rotate((state.rotate * Math.PI) / 180);
            context.drawImage(
                state.image,
                (-state.image.width * scale) / 2,
                (-state.image.height * scale) / 2,
                state.image.width * scale,
                state.image.height * scale,
            );
            context.restore();

            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            cropInput.value = dataUrl;

            if (preview.tagName === 'IMG') {
                preview.src = dataUrl;
                preview.hidden = false;
            }

            if (placeholder) {
                placeholder.hidden = true;
            }
        };

        const resetCrop = () => {
            state.offsetX = 0;
            state.offsetY = 0;
            state.zoom = 1;
            state.rotate = 0;
            zoom.value = '1';
            rotate.value = '0';
            draw();
        };

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];

            if (!file) {
                cropInput.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = () => {
                const image = new Image();
                image.onload = () => {
                    state.image = image;
                    workbench.hidden = false;
                    resetCrop();
                };
                image.src = reader.result;
            };

            reader.readAsDataURL(file);
        });

        zoom.addEventListener('input', () => {
            state.zoom = Number(zoom.value);
            draw();
        });

        rotate.addEventListener('input', () => {
            state.rotate = Number(rotate.value);
            draw();
        });

        reset.addEventListener('click', resetCrop);

        canvas.addEventListener('pointerdown', (event) => {
            state.dragging = true;
            state.lastX = event.clientX;
            state.lastY = event.clientY;
            canvas.setPointerCapture(event.pointerId);
        });

        canvas.addEventListener('pointermove', (event) => {
            if (!state.dragging) {
                return;
            }

            const rect = canvas.getBoundingClientRect();
            const ratio = canvas.width / rect.width;
            state.offsetX += (event.clientX - state.lastX) * ratio;
            state.offsetY += (event.clientY - state.lastY) * ratio;
            state.lastX = event.clientX;
            state.lastY = event.clientY;
            draw();
        });

        canvas.addEventListener('pointerup', () => {
            state.dragging = false;
        });
    });
});
