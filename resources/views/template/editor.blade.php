<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Editor — {{ $template->name }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --ink:        #0d0d0d;
            --panel-bg:   #13151a;
            --panel-sec:  #1c1f27;
            --panel-bdr:  #2a2d38;
            --red:        #c0392b;
            --red-soft:   rgba(192,57,43,.15);
            --gold:       #b8972a;
            --gold-soft:  rgba(184,151,42,.12);
            --text-pri:   #e8e6e1;
            --text-sec:   #8a8790;
            --text-dim:   #55535e;
            --canvas-bg:  #23262f;
            --white:      #ffffff;
            --success:    #27ae60;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            overflow: hidden;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            background: var(--canvas-bg);
            color: var(--text-pri);
        }

        /* ══ Layout ══ */
        .editor-shell { display: flex; flex-direction: column; height: 100vh; }

        /* ── Top bar ── */
        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            height: 52px; background: var(--panel-bg); border-bottom: 1px solid var(--panel-bdr);
            padding: 0 16px; flex-shrink: 0; z-index: 10;
        }

        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .topbar-brand { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--red); letter-spacing: .04em; }
        .topbar-divider { width: 1px; height: 20px; background: var(--panel-bdr); }
        .topbar-template-name { font-size: 13px; color: var(--text-sec); }
        .topbar-template-name strong { color: var(--text-pri); font-weight: 500; }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .tbtn {
            display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px;
            border-radius: 4px; font-size: 12.5px; font-weight: 500; cursor: pointer;
            border: none; font-family: 'DM Sans', sans-serif; transition: all .15s;
        }

        .tbtn-ghost { background: transparent; color: var(--text-sec); border: 1px solid var(--panel-bdr); }
        .tbtn-ghost:hover { color: var(--text-pri); border-color: var(--text-dim); }
        .tbtn-save { background: var(--red); color: var(--white); }
        .tbtn-save:hover { background: #a93226; }
        .tbtn-export { background: var(--gold-soft); color: var(--gold); border: 1px solid rgba(184,151,42,.3); }
        .tbtn-export:hover { background: rgba(184,151,42,.22); }
        .tbtn-back { background: transparent; color: var(--text-dim); font-size: 12px; }
        .tbtn-back:hover { color: var(--text-pri); }

        /* ── Main area ── */
        .editor-main { display: flex; flex: 1; overflow: hidden; }

        /* ── Left panel ── */
        .panel-left {
            width: 260px; background: var(--panel-bg); border-right: 1px solid var(--panel-bdr);
            display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto;
        }

        .panel-section { border-bottom: 1px solid var(--panel-bdr); padding: 14px 14px 10px; }
        .panel-section-title {
            font-size: 10px; font-weight: 600; letter-spacing: .13em;
            text-transform: uppercase; color: var(--text-dim); margin-bottom: 10px;
        }

        .el-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
        .el-btn {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 4px; padding: 10px 6px; border-radius: 5px; border: 1px solid var(--panel-bdr);
            background: var(--panel-sec); color: var(--text-sec); font-size: 10px; font-weight: 500;
            cursor: pointer; transition: all .14s; text-align: center;
        }
        .el-btn i { font-size: 15px; color: var(--red); }
        .el-btn:hover { background: var(--red-soft); border-color: var(--red); color: var(--text-pri); }

        .el-btn-full {
            width: 100%; display: flex; align-items: center; gap: 9px; padding: 9px 12px;
            border-radius: 5px; border: 1px solid var(--panel-bdr); background: var(--panel-sec);
            color: var(--text-sec); font-size: 12px; font-weight: 500; cursor: pointer;
            transition: all .14s; margin-bottom: 5px;
        }
        .el-btn-full i { font-size: 13px; color: var(--gold); width: 16px; text-align: center; }
        .el-btn-full:hover { background: var(--gold-soft); border-color: var(--gold); color: var(--text-pri); }
        .el-btn-full span.el-tag {
            margin-left: auto; font-size: 9px; background: var(--panel-bdr);
            padding: 1px 5px; border-radius: 3px; color: var(--text-dim); font-family: monospace;
        }

        /* ── Canvas area ── */
        .canvas-wrap {
            flex: 1; display: flex; align-items: center; justify-content: center;
            background: var(--canvas-bg);
            background-image: radial-gradient(circle at 20px 20px, rgba(255,255,255,.03) 1px, transparent 0);
            background-size: 20px 20px; overflow: auto; position: relative;
        }
        .canvas-label {
            position: absolute; top: 14px; left: 50%; transform: translateX(-50%);
            background: rgba(0,0,0,.5); color: var(--text-dim); font-size: 10px;
            letter-spacing: .1em; text-transform: uppercase; padding: 3px 12px;
            border-radius: 999px; pointer-events: none;
        }
        #canvas { display: block; box-shadow: 0 8px 60px rgba(0,0,0,.55); }

        /* ── Right panel ── */
        .panel-right {
            width: 240px; background: var(--panel-bg); border-left: 1px solid var(--panel-bdr);
            display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto;
        }

        .prop-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
        .prop-label { font-size: 11px; color: var(--text-sec); font-weight: 400; }
        .prop-input, .prop-select {
            background: var(--panel-sec); border: 1px solid var(--panel-bdr);
            color: var(--text-pri); border-radius: 4px; padding: 5px 8px; font-size: 12px; width: 110px;
        }
        .prop-select { width: 100%; margin-bottom: 8px; }
        .prop-color { width: 36px; height: 28px; border-radius: 4px; border: 1px solid var(--panel-bdr); background: none; cursor: pointer; padding: 2px; }
        
        .prop-btn {
            width: 100%; padding: 8px; background: var(--red-soft); color: var(--red);
            border: 1px solid rgba(192,57,43,.3); border-radius: 4px; font-size: 12px;
            font-weight: 500; cursor: pointer; transition: all .14s; margin-bottom: 6px;
        }
        .prop-btn:hover { background: var(--red); color: var(--white); }

        .icon-row { display: flex; gap: 5px; margin-bottom: 6px; }
        .icon-btn {
            flex: 1; padding: 8px 0; border-radius: 4px; border: 1px solid var(--panel-bdr);
            background: var(--panel-sec); color: var(--text-sec); font-size: 12px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .icon-btn:hover { background: var(--panel-bdr); color: var(--text-pri); }

        /* ── File upload styling ── */
        .upload-area {
            border: 1.5px dashed var(--panel-bdr); border-radius: 6px; padding: 14px;
            text-align: center; color: var(--text-dim); cursor: pointer; position: relative;
        }
        .upload-area:hover { border-color: var(--gold); color: var(--gold); }
        .upload-area i { font-size: 20px; margin-bottom: 5px; display: block; }
        .upload-area p { font-size: 11px; }
        .upload-area input[type="file"] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--panel-bdr); border-radius: 99px; }
    </style>
</head>
<body>

<div class="editor-shell">
    <!-- Top bar -->
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('templates.index') }}" class="tbtn tbtn-back"><i class="fa fa-arrow-left"></i> Back</a>
            <div class="topbar-divider"></div>
            <div class="topbar-brand">&#9646; TKD CERT</div>
            <div class="topbar-divider"></div>
            <div class="topbar-template-name">
                Editing: <strong>{{ $template->name }}</strong>
                &nbsp;·&nbsp;
                <span style="color:var(--text-dim);font-size:11px;">{{ ucfirst($template->type) ?? 'Certificate' }}</span>
            </div>
        </div>
        <div class="topbar-actions">
            <button class="tbtn tbtn-ghost" onclick="previewCert()"><i class="fa fa-eye"></i> Preview</button>
            <button class="tbtn tbtn-save" onclick="saveLayout()"><i class="fa fa-floppy-disk"></i> Save Template</button>
        </div>
    </div>

    <div class="editor-main">
        <!-- ═══ LEFT PANEL ═══ -->
        <div class="panel-left">

            <!-- 1. Background -->
            <div class="panel-section">
                <div class="panel-section-title">1. Certificate Background</div>
                <div class="upload-area">
                    <i class="fa fa-image"></i>
                    <p>Upload Background<br>(A4 Landscape Image)</p>
                    <input type="file" id="bgUpload" accept="image/*">
                </div>
            </div>

            <!-- 2. Static Text (Headers & Labels) -->
            <div class="panel-section">
                <div class="panel-section-title">2. Static Text & Labels</div>
                <button class="el-btn-full" onclick="addStaticText('CERTIFICATE OF PROMOTION', 36, 'Cinzel, serif', 'bold')">
                    <i class="fa fa-heading"></i> Main Header Text
                </button>
                <button class="el-btn-full" onclick="addStaticText('This is to certify that', 16, 'DM Sans, sans-serif', 'normal')">
                    <i class="fa fa-paragraph"></i> Body Text Line
                </button>
                <div class="el-grid mt-1">
                    <button class="el-btn" onclick="addStaticText('Master Reyes', 24, 'Cinzel, serif', 'bold')">
                        <i class="fa fa-pen-nib"></i> Sig. Name
                    </button>
                    <button class="el-btn" onclick="addStaticText('Head Instructor', 14, 'DM Sans, sans-serif', 'normal')">
                        <i class="fa fa-user-tie"></i> Sig. Title
                    </button>
                </div>
            </div>

            <!-- 3. Dynamic Database Fields -->
            <div class="panel-section">
                <div class="panel-section-title">3. Dynamic Database Fields</div>
                <div class="el-grid">
                    <button class="el-btn" onclick="addField('@{{student_name}}')"><i class="fa fa-user"></i> Student Name</button>
                    <button class="el-btn" onclick="addField('@{{belt_level}}')"><i class="fa fa-belt-karate" style="font-size:14px"></i> Belt Level</button>
                    <button class="el-btn" onclick="addField('@{{dan_rank}}')"><i class="fa fa-star"></i> Dan Rank</button>
                    <button class="el-btn" onclick="addField('@{{date_issued}}')"><i class="fa fa-calendar"></i> Date Issued</button>
                </div>
                <button class="el-btn-full mt-2" onclick="addField('@{{competition_name}}')">
                    <i class="fa fa-trophy"></i> Competition Name <span class="el-tag">event</span>
                </button>
            </div>

            <!-- 4. Overlays & Signatures -->
            <div class="panel-section">
                <div class="panel-section-title">4. Signatures & Logos</div>
                <div class="upload-area mb-2">
                    <i class="fa fa-signature"></i>
                    <p>Upload Signature Image<br>PNG (Transparent)</p>
                    <input type="file" id="sigUpload" accept="image/*">
                </div>
                <button class="el-btn-full" onclick="addQRCode()">
                    <i class="fa fa-qrcode"></i> Verification QR Code
                </button>
            </div>
            
            <!-- Arrange -->
            <div class="panel-section">
                <div class="panel-section-title">Arrange</div>
                <div class="icon-row">
                    <button class="icon-btn" onclick="bringFront()" title="Bring Front"><i class="fa fa-arrow-up"></i></button>
                    <button class="icon-btn" onclick="sendBack()" title="Send Back"><i class="fa fa-arrow-down"></i></button>
                    <button class="icon-btn" onclick="deleteObj()" title="Delete" style="color:#e74c3c"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div>

        <!-- ═══ CANVAS ═══ -->
        <div class="canvas-wrap" id="canvasWrap">
            <div class="canvas-label">A4 Landscape — 1123 × 794 px</div>
            <canvas id="canvas" width="1123" height="794"></canvas>
        </div>

        <!-- ═══ RIGHT PANEL ═══ -->
        <div class="panel-right">
            <!-- Typography -->
            <div class="panel-section">
                <div class="panel-section-title">Typography & Style</div>

                <div class="prop-label" style="margin-bottom:5px;">Font Family</div>
                <select class="prop-select" id="fontFamily">
                    <option value="Cinzel, serif">Cinzel (Traditional)</option>
                    <option value="'DM Sans', sans-serif">DM Sans (Modern)</option>
                    <option value="Georgia, serif">Georgia (Classic)</option>
                    <option value="'Times New Roman', serif">Times New Roman</option>
                    <option value="Arial, sans-serif">Arial</option>
                </select>

                <div class="prop-row">
                    <label class="prop-label">Font Size</label>
                    <input type="number" class="prop-input" id="fontSize" placeholder="20" min="6" max="120">
                </div>

                <div class="prop-row">
                    <label class="prop-label">Text Color</label>
                    <input type="color" class="prop-color" id="fontColor" value="#0d0d0d">
                </div>

                <div class="prop-row">
                    <label class="prop-label">Bold</label>
                    <button class="icon-btn" onclick="toggleBold()" style="width:auto;padding:5px 14px;"><i class="fa fa-bold"></i></button>
                </div>

                <div class="prop-row">
                    <label class="prop-label">Align</label>
                    <div class="icon-row" style="margin:0;gap:3px;">
                        <button class="icon-btn" onclick="setAlign('left')" style="width:auto;padding:5px 9px;"><i class="fa fa-align-left"></i></button>
                        <button class="icon-btn" onclick="setAlign('center')" style="width:auto;padding:5px 9px;"><i class="fa fa-align-center"></i></button>
                        <button class="icon-btn" onclick="setAlign('right')" style="width:auto;padding:5px 9px;"><i class="fa fa-align-right"></i></button>
                    </div>
                </div>
                <button class="prop-btn" onclick="applyStyle()"><i class="fa fa-check"></i> Apply Style</button>
            </div>
            
             <!-- Canvas BG Fallback -->
             <div class="panel-section">
                <div class="panel-section-title">Fallback BG Color</div>
                <div class="prop-row">
                    <label class="prop-label">Color</label>
                    <input type="color" class="prop-color" id="canvasBg" value="#ffffff" onchange="setCanvasBg(this.value)">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/* ═══════════════════════════════════════
   CANVAS INIT
═══════════════════════════════════════ */
const canvas = new fabric.Canvas('canvas', { selection: true });
canvas.setBackgroundColor('#ffffff', canvas.renderAll.bind(canvas));

// Grid snapping
const grid = 10;
canvas.on('object:moving', function(opt) {
    opt.target.set({
        left: Math.round(opt.target.left / grid) * grid,
        top:  Math.round(opt.target.top  / grid) * grid
    });
});

/* ═══════════════════════════════════════
   LOAD SAVED LAYOUT
═══════════════════════════════════════ */
let savedLayout = @json($template->layout);

if (savedLayout && Object.keys(savedLayout).length > 0 && !savedLayout.objects) {
    // If it's a raw fabric JSON dump
    canvas.loadFromJSON(savedLayout, function() {
        canvas.renderAll();
    });
} else if (savedLayout && savedLayout.objects) {
    // Standard parse
    canvas.loadFromJSON(savedLayout, function() {
        canvas.renderAll();
    });
}

/* ═══════════════════════════════════════
   TEXT ELEMENTS (STATIC AND DYNAMIC)
═══════════════════════════════════════ */
function addStaticText(text, size, font, weight) {
    const t = new fabric.IText(text, {
        left: 400, top: 200,
        fontSize: size,
        fontFamily: font,
        fontWeight: weight,
        fill: '#0d0d0d',
        textAlign: 'center'
    });
    canvas.add(t);
    canvas.setActiveObject(t);
}

function addField(key) {
    const t = new fabric.IText(key, {
        left: 400, top: 400,
        fontSize: 24,
        fontFamily: 'DM Sans, sans-serif',
        fill: '#c0392b', // Make dynamic fields red in the editor to stand out
        fontWeight: 'bold',
        textAlign: 'center'
    });
    canvas.add(t);
    canvas.setActiveObject(t);
}

function addQRCode() {
    const rect = new fabric.Rect({
        left: 0, top: 0, width: 80, height: 80,
        fill: '#f5f5f5', stroke: '#ccc', strokeWidth: 1
    });
    const label = new fabric.Text('@{{qr_code}}', {
        left: 10, top: 30, fontSize: 10, fill: '#888'
    });
    const group = new fabric.Group([rect, label], { left: 950, top: 650 });
    canvas.add(group);
    canvas.setActiveObject(group);
}

/* ═══════════════════════════════════════
   BACKGROUND UPLOAD (Saves via AJAX & Sets BG)
═══════════════════════════════════════ */
document.getElementById('bgUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    Swal.fire({ title: 'Setting Background...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch('/templates/{{ $template->id }}/upload-image', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            fabric.Image.fromURL(data.url, function(img) {
                // Force scale to exactly fit the A4 canvas
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    scaleX: canvas.width / img.width,
                    scaleY: canvas.height / img.height
                });
                Swal.close();
            });
        }
    })
    .catch(() => Swal.fire('Error', 'Failed to upload background.', 'error'));
    e.target.value = '';
});

/* ═══════════════════════════════════════
   SIGNATURE / OVERLAY UPLOAD
═══════════════════════════════════════ */
document.getElementById('sigUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    Swal.fire({ title: 'Uploading Signature...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch('/templates/{{ $template->id }}/upload-image', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            fabric.Image.fromURL(data.url, function(img) {
                img.set({ left: 400, top: 600 });
                // Scale down large images
                if (img.width > 200) img.scaleToWidth(200);
                canvas.add(img);
                canvas.setActiveObject(img);
                canvas.renderAll();
                Swal.close();
            });
        }
    });
    e.target.value = '';
});

/* ═══════════════════════════════════════
   STYLE MANIPULATION
═══════════════════════════════════════ */
function applyStyle() {
    const obj = canvas.getActiveObject();
    if (!obj) return;
    const fontSize   = parseInt(document.getElementById('fontSize').value);
    const fill       = document.getElementById('fontColor').value;
    const fontFamily = document.getElementById('fontFamily').value;
    const updates    = {};
    if (fontSize)    updates.fontSize   = fontSize;
    if (fill)        updates.fill       = fill;
    if (fontFamily)  updates.fontFamily = fontFamily;
    
    if (obj.type === 'text' || obj.type === 'i-text') {
        obj.set(updates);
    }
    canvas.renderAll();
}

function toggleBold() {
    const obj = canvas.getActiveObject();
    if (obj && (obj.type === 'text' || obj.type === 'i-text')) {
        obj.set('fontWeight', obj.fontWeight === 'bold' ? 'normal' : 'bold');
        canvas.renderAll();
    }
}
function setAlign(align) {
    const obj = canvas.getActiveObject();
    if (obj && (obj.type === 'text' || obj.type === 'i-text')) {
        obj.set('textAlign', align);
        canvas.renderAll();
    }
}
function setCanvasBg(color) {
    canvas.setBackgroundColor(color, canvas.renderAll.bind(canvas));
}

// Property sync when clicking elements
canvas.on('selection:created', syncProps);
canvas.on('selection:updated', syncProps);
function syncProps() {
    const obj = canvas.getActiveObject();
    if (!obj) return;
    if (obj.type === 'text' || obj.type === 'i-text') {
        if (obj.fontSize) document.getElementById('fontSize').value = obj.fontSize;
        if (obj.fill)     document.getElementById('fontColor').value = obj.fill;
    }
}

/* ═══════════════════════════════════════
   ARRANGE & SAVE
═══════════════════════════════════════ */
function bringFront() { const o = canvas.getActiveObject(); if(o) canvas.bringToFront(o); }
function sendBack()   { const o = canvas.getActiveObject(); if(o) canvas.sendToBack(o); }
function deleteObj()  { const o = canvas.getActiveObject(); if(o) canvas.remove(o); }

// Keyboard delete
document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    if (e.key === 'Delete' || e.key === 'Backspace') { 
        const obj = canvas.getActiveObject();
        if(obj && !obj.isEditing) canvas.remove(obj); 
    }
});

function previewCert() {
    const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
    Swal.fire({
        title: 'Template Preview',
        html: `<img src="${dataURL}" style="width:100%;border-radius:4px;border:1px solid #333;">`,
        width: 900,
        background: '#13151a', color: '#e8e6e1', confirmButtonColor: '#c0392b'
    });
}

function saveLayout() {
    // Grab everything including the background image URL!
    const fabricJSON = canvas.toJSON();

    Swal.fire({
        title: 'Saving Template…',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch('/templates/{{ $template->id }}/save-layout', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            // Send exactly what the controller expects
            layout: fabricJSON 
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            Swal.fire({ icon: 'success', title: 'Saved!', background: '#13151a', color: '#e8e6e1', confirmButtonColor: '#c0392b' });
        } else {
            throw new Error('Save failed');
        }
    })
    .catch(() => Swal.fire('Error', 'Could not save layout. Check your connection.', 'error'));
}
</script>

</body>
</html>