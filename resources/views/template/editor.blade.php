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
        .editor-shell {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* ── Top bar ── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 52px;
            background: var(--panel-bg);
            border-bottom: 1px solid var(--panel-bdr);
            padding: 0 16px;
            flex-shrink: 0;
            z-index: 10;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-brand {
            font-family: 'Cinzel', serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--red);
            letter-spacing: .04em;
        }

        .topbar-divider {
            width: 1px;
            height: 20px;
            background: var(--panel-bdr);
        }

        .topbar-template-name {
            font-size: 13px;
            color: var(--text-sec);
        }
        .topbar-template-name strong {
            color: var(--text-pri);
            font-weight: 500;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tbtn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 4px;
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            letter-spacing: .01em;
        }

        .tbtn-ghost {
            background: transparent;
            color: var(--text-sec);
            border: 1px solid var(--panel-bdr);
        }
        .tbtn-ghost:hover { color: var(--text-pri); border-color: var(--text-dim); }

        .tbtn-save {
            background: var(--red);
            color: var(--white);
        }
        .tbtn-save:hover { background: #a93226; }

        .tbtn-export {
            background: var(--gold-soft);
            color: var(--gold);
            border: 1px solid rgba(184,151,42,.3);
        }
        .tbtn-export:hover { background: rgba(184,151,42,.22); }

        .tbtn-back {
            background: transparent;
            color: var(--text-dim);
            font-size: 12px;
        }
        .tbtn-back:hover { color: var(--text-pri); }

        /* ── Main area ── */
        .editor-main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ── Left panel ── */
        .panel-left {
            width: 240px;
            background: var(--panel-bg);
            border-right: 1px solid var(--panel-bdr);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow-y: auto;
        }

        .panel-section {
            border-bottom: 1px solid var(--panel-bdr);
            padding: 14px 14px 10px;
        }

        .panel-section-title {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .13em;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: 10px;
        }

        /* Element buttons */
        .el-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }

        .el-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 10px 6px;
            border-radius: 5px;
            border: 1px solid var(--panel-bdr);
            background: var(--panel-sec);
            color: var(--text-sec);
            font-size: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all .14s;
            font-family: 'DM Sans', sans-serif;
            text-align: center;
        }
        .el-btn i { font-size: 15px; color: var(--red); }
        .el-btn:hover { background: var(--red-soft); border-color: var(--red); color: var(--text-pri); }
        .el-btn:hover i { color: var(--red); }

        /* Full-width element btn */
        .el-btn-full {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            border-radius: 5px;
            border: 1px solid var(--panel-bdr);
            background: var(--panel-sec);
            color: var(--text-sec);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all .14s;
            margin-bottom: 5px;
            font-family: 'DM Sans', sans-serif;
        }
        .el-btn-full i { font-size: 13px; color: var(--gold); width: 16px; text-align: center; }
        .el-btn-full:hover { background: var(--gold-soft); border-color: var(--gold); color: var(--text-pri); }
        .el-btn-full span.el-tag {
            margin-left: auto;
            font-size: 9px;
            background: var(--panel-bdr);
            padding: 1px 5px;
            border-radius: 3px;
            color: var(--text-dim);
            font-family: monospace;
        }

        /* ── Canvas area ── */
        .canvas-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--canvas-bg);
            background-image:
                radial-gradient(circle at 20px 20px, rgba(255,255,255,.03) 1px, transparent 0);
            background-size: 20px 20px;
            overflow: auto;
            position: relative;
        }

        .canvas-label {
            position: absolute;
            top: 14px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,.5);
            color: var(--text-dim);
            font-size: 10px;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 3px 12px;
            border-radius: 999px;
            pointer-events: none;
        }

        #canvas {
            display: block;
            box-shadow: 0 8px 60px rgba(0,0,0,.55);
        }

        /* ── Right panel ── */
        .panel-right {
            width: 240px;
            background: var(--panel-bg);
            border-left: 1px solid var(--panel-bdr);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow-y: auto;
        }

        /* Property row */
        .prop-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .prop-label {
            font-size: 11px;
            color: var(--text-sec);
            font-weight: 400;
        }

        .prop-input {
            background: var(--panel-sec);
            border: 1px solid var(--panel-bdr);
            color: var(--text-pri);
            border-radius: 4px;
            padding: 5px 8px;
            font-size: 12px;
            font-family: 'DM Sans', sans-serif;
            width: 110px;
            transition: border-color .14s;
        }
        .prop-input:focus { outline: none; border-color: var(--red); }

        .prop-input-full {
            width: 100%;
            margin-bottom: 8px;
        }

        .prop-select {
            background: var(--panel-sec);
            border: 1px solid var(--panel-bdr);
            color: var(--text-pri);
            border-radius: 4px;
            padding: 6px 8px;
            font-size: 12px;
            font-family: 'DM Sans', sans-serif;
            width: 100%;
            margin-bottom: 8px;
            cursor: pointer;
        }
        .prop-select:focus { outline: none; border-color: var(--red); }

        .prop-color {
            width: 36px;
            height: 28px;
            border-radius: 4px;
            border: 1px solid var(--panel-bdr);
            background: none;
            cursor: pointer;
            padding: 2px;
        }

        .prop-btn {
            width: 100%;
            padding: 8px;
            background: var(--red-soft);
            color: var(--red);
            border: 1px solid rgba(192,57,43,.3);
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .14s;
            margin-bottom: 6px;
        }
        .prop-btn:hover { background: var(--red); color: var(--white); }

        .icon-row {
            display: flex;
            gap: 5px;
            margin-bottom: 6px;
        }

        .icon-btn {
            flex: 1;
            padding: 8px 0;
            border-radius: 4px;
            border: 1px solid var(--panel-bdr);
            background: var(--panel-sec);
            color: var(--text-sec);
            font-size: 12px;
            cursor: pointer;
            transition: all .14s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-btn:hover { background: var(--panel-bdr); color: var(--text-pri); }

        /* ── Zoom indicator ── */
        .zoom-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 14px;
            border-top: 1px solid var(--panel-bdr);
            background: var(--panel-bg);
        }

        .zoom-pct {
            font-size: 11px;
            color: var(--text-dim);
            width: 36px;
            text-align: center;
        }

        /* ── File upload styling ── */
        .upload-area {
            border: 1.5px dashed var(--panel-bdr);
            border-radius: 6px;
            padding: 14px;
            text-align: center;
            color: var(--text-dim);
            cursor: pointer;
            transition: all .15s;
            position: relative;
        }
        .upload-area:hover { border-color: var(--gold); color: var(--gold); }
        .upload-area i { font-size: 20px; margin-bottom: 5px; display: block; }
        .upload-area p { font-size: 11px; }
        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--panel-bdr); border-radius: 99px; }

        /* Belt color selector */
        .belt-colors {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        .belt-swatch {
            width: 22px;
            height: 22px;
            border-radius: 3px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color .12s;
        }
        .belt-swatch:hover, .belt-swatch.selected { border-color: var(--white); }
    </style>
</head>
<body>

<div class="editor-shell">

    <!-- Top bar -->
    <div class="topbar">
        <div class="topbar-left">
            <a href="/templates" class="tbtn tbtn-back">
                <i class="fa fa-arrow-left"></i> Back
            </a>
            <div class="topbar-divider"></div>
            <div class="topbar-brand">&#9646; TKD CERT</div>
            <div class="topbar-divider"></div>
            <div class="topbar-template-name">
                Editing: <strong>{{ $template->name }}</strong>
                &nbsp;·&nbsp;
                <span style="color:var(--text-dim);font-size:11px;">{{ $template->type ?? 'Certificate' }}</span>
            </div>
        </div>
        <div class="topbar-actions">
            <button class="tbtn tbtn-ghost" onclick="undo()"><i class="fa fa-rotate-left"></i></button>
            <button class="tbtn tbtn-ghost" onclick="redo()"><i class="fa fa-rotate-right"></i></button>
            <div style="width:1px;height:20px;background:var(--panel-bdr);"></div>
            <button class="tbtn tbtn-ghost" onclick="previewCert()"><i class="fa fa-eye"></i> Preview</button>
            <button class="tbtn tbtn-export" onclick="exportPNG()"><i class="fa fa-file-image"></i> Export PNG</button>
            <button class="tbtn tbtn-save" onclick="saveLayout()"><i class="fa fa-floppy-disk"></i> Save</button>
        </div>
    </div>

    <!-- Main -->
    <div class="editor-main">

        <!-- ═══ LEFT PANEL ═══ -->
        <div class="panel-left">

            <!-- Student / Person Fields -->
            <div class="panel-section">
                <div class="panel-section-title">Student Fields</div>
                <div class="el-grid">
                    <button onclick="addField('@{{student_name}}')">
                        <i class="fa fa-user"></i> Student Name
                    </button>
                    <button class="el-btn" onclick="addField('@{{belt_level}}')">
                        <i class="fa fa-belt-karate" style="font-size:14px"></i> Belt Level
                    </button>
                    <button class="el-btn" onclick="addField('@{{dan_rank}}')">
                        <i class="fa fa-star"></i> Dan Rank
                    </button>
                    <button class="el-btn" onclick="addField('@{{date_issued}}')">
                        <i class="fa fa-calendar"></i> Date Issued
                    </button>
                </div>
            </div>

            <!-- Certificate Fields -->
            <div class="panel-section">
                <div class="panel-section-title">Certificate Fields</div>
                <button class="el-btn-full" onclick="addField('@{{certificate_title}}')">
                    <i class="fa fa-certificate"></i>
                    Certificate Title
                    <span class="el-tag">title</span>
                </button>
                <button class="el-btn-full" onclick="addField('@{{korean_title}}')">
                    <i class="fa fa-language"></i>
                    Korean Title
                    <span class="el-tag">한국어</span>
                </button>
                <button class="el-btn-full" onclick="addField('@{{kukkiwon_number}}')">
                    <i class="fa fa-hashtag"></i>
                    Kukkiwon Ref No.
                    <span class="el-tag">ref</span>
                </button>
                <button class="el-btn-full" onclick="addField('@{{club_name}}')">
                    <i class="fa fa-shield-halved"></i>
                    Club Name
                    <span class="el-tag">club</span>
                </button>
                <button class="el-btn-full" onclick="addField('@{{competition_name}}')">
                    <i class="fa fa-trophy"></i>
                    Competition Name
                    <span class="el-tag">event</span>
                </button>
                <button class="el-btn-full" onclick="addField('@{{medal}}')">
                    <i class="fa fa-medal"></i>
                    Medal / Award
                    <span class="el-tag">award</span>
                </button>
            </div>

            <!-- Instructor Fields -->
            <div class="panel-section">
                <div class="panel-section-title">Instructor / Authority</div>
                <button class="el-btn-full" onclick="addField('@{{instructor_name}}')">
                    <i class="fa fa-person"></i>
                    Instructor Name
                    <span class="el-tag">instr</span>
                </button>
                <button class="el-btn-full" onclick="addField('@{{master_name}}')">
                    <i class="fa fa-ranking-star"></i>
                    Master Name
                    <span class="el-tag">master</span>
                </button>
            </div>

            <!-- Special Elements -->
            <div class="panel-section">
                <div class="panel-section-title">Special Elements</div>
                <button class="el-btn-full" onclick="addQRCode()">
                    <i class="fa fa-qrcode"></i>
                    QR Code
                    <span class="el-tag">verify</span>
                </button>
                <button class="el-btn-full" onclick="addSealPlaceholder()">
                    <i class="fa fa-stamp"></i>
                    Seal / Stamp
                    <span class="el-tag">seal</span>
                </button>
                <button class="el-btn-full" onclick="addSignatureLine()">
                    <i class="fa fa-signature"></i>
                    Signature Line
                    <span class="el-tag">sig</span>
                </button>
            </div>

            <!-- Upload -->
            <div class="panel-section">
                <div class="panel-section-title">Upload Image / Logo</div>
                <div class="upload-area">
                    <i class="fa fa-cloud-arrow-up"></i>
                    <p>Click or drag image<br>PNG, JPG, SVG</p>
                    <input type="file" id="imageUpload" accept="image/*">
                </div>
            </div>

            <!-- History -->
            <div class="panel-section">
                <div class="panel-section-title">History</div>
                <div class="icon-row">
                    <button class="icon-btn" onclick="undo()" title="Undo"><i class="fa fa-rotate-left"></i> Undo</button>
                    <button class="icon-btn" onclick="redo()" title="Redo"><i class="fa fa-rotate-right"></i> Redo</button>
                </div>
            </div>

            <!-- Group / Layer / Lock -->
            <div class="panel-section">
                <div class="panel-section-title">Arrange</div>
                <div class="icon-row">
                    <button class="icon-btn" onclick="groupObjects()" title="Group"><i class="fa fa-object-group"></i></button>
                    <button class="icon-btn" onclick="ungroupObjects()" title="Ungroup"><i class="fa fa-object-ungroup"></i></button>
                    <button class="icon-btn" onclick="bringFront()" title="Bring Front"><i class="fa fa-arrow-up"></i></button>
                    <button class="icon-btn" onclick="sendBack()" title="Send Back"><i class="fa fa-arrow-down"></i></button>
                </div>
                <div class="icon-row">
                    <button class="icon-btn" onclick="lockObject()" title="Lock"><i class="fa fa-lock"></i> Lock</button>
                    <button class="icon-btn" onclick="unlockAll()" title="Unlock All"><i class="fa fa-unlock"></i> All</button>
                </div>
                <div class="icon-row">
                    <button class="icon-btn" onclick="duplicate()" title="Duplicate"><i class="fa fa-clone"></i> Duplicate</button>
                    <button class="icon-btn" onclick="deleteObj()" title="Delete" style="color:#e74c3c"><i class="fa fa-trash"></i> Delete</button>
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

            <!-- Font Style -->
            <div class="panel-section">
                <div class="panel-section-title">Typography</div>

                <div class="prop-label" style="margin-bottom:5px;">Font Family</div>
                <select class="prop-select" id="fontFamily">
                    <option value="Cinzel, serif">Cinzel (Traditional)</option>
                    <option value="'DM Sans', sans-serif" selected>DM Sans (Modern)</option>
                    <option value="Georgia, serif">Georgia (Classic)</option>
                    <option value="'Times New Roman', serif">Times New Roman</option>
                    <option value="'Nanum Myeongjo', serif">Nanum Myeongjo (Korean)</option>
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
                    <label class="prop-label">Italic</label>
                    <button class="icon-btn" onclick="toggleItalic()" style="width:auto;padding:5px 14px;"><i class="fa fa-italic"></i></button>
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

            <!-- Language -->
            <div class="panel-section">
                <div class="panel-section-title">Language</div>
                <select class="prop-select" id="langMode">
                    <option value="english">English</option>
                    <option value="korean">Korean</option>
                    <option value="dual">Dual (EN + KR)</option>
                </select>
            </div>

            <!-- Belt Color Accent -->
            <div class="panel-section">
                <div class="panel-section-title">Belt Color Accent</div>
                <div class="belt-colors" id="beltPalette">
                    <div class="belt-swatch" style="background:#f0f0f0;border:1px solid #aaa;" title="White" onclick="selectBelt(this,'#f0f0f0')"></div>
                    <div class="belt-swatch" style="background:#f5c518;" title="Yellow" onclick="selectBelt(this,'#f5c518')"></div>
                    <div class="belt-swatch" style="background:#f0a500;" title="Orange" onclick="selectBelt(this,'#f0a500')"></div>
                    <div class="belt-swatch" style="background:#27ae60;" title="Green" onclick="selectBelt(this,'#27ae60')"></div>
                    <div class="belt-swatch" style="background:#2980b9;" title="Blue" onclick="selectBelt(this,'#2980b9')"></div>
                    <div class="belt-swatch" style="background:#8e44ad;" title="Purple" onclick="selectBelt(this,'#8e44ad')"></div>
                    <div class="belt-swatch" style="background:#c0392b;" title="Red" onclick="selectBelt(this,'#c0392b')"></div>
                    <div class="belt-swatch selected" style="background:#0d0d0d;" title="Black" onclick="selectBelt(this,'#0d0d0d')"></div>
                </div>
            </div>

            <!-- Canvas Background -->
            <div class="panel-section">
                <div class="panel-section-title">Canvas Background</div>
                <div class="prop-row">
                    <label class="prop-label">BG Color</label>
                    <input type="color" class="prop-color" id="canvasBg" value="#ffffff" onchange="setCanvasBg(this.value)">
                </div>
            </div>

            <!-- Position / Size -->
            <div class="panel-section">
                <div class="panel-section-title">Position & Size</div>
                <div class="prop-row">
                    <label class="prop-label">X</label>
                    <input type="number" class="prop-input" id="objX" placeholder="0">
                </div>
                <div class="prop-row">
                    <label class="prop-label">Y</label>
                    <input type="number" class="prop-input" id="objY" placeholder="0">
                </div>
                <div class="prop-row">
                    <label class="prop-label">Opacity</label>
                    <input type="range" min="0" max="1" step="0.05" id="objOpacity" value="1" style="width:110px;" onchange="setOpacity(this.value)">
                </div>
                <button class="prop-btn" onclick="applyPosition()"><i class="fa fa-crosshairs"></i> Apply Position</button>
            </div>

            <!-- Zoom -->
            <div class="zoom-bar">
                <button class="icon-btn" onclick="zoomOut()" style="width:32px;height:28px;flex:none;"><i class="fa fa-minus"></i></button>
                <div class="zoom-pct" id="zoomLabel">100%</div>
                <button class="icon-btn" onclick="zoomIn()" style="width:32px;height:28px;flex:none;"><i class="fa fa-plus"></i></button>
                <button class="icon-btn" onclick="resetZoom()" style="flex:none;padding:0 10px;height:28px;font-size:10px;margin-left:4px;">Reset</button>
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

/* ═══════════════════════════════════════
   LOAD SAVED LAYOUT — use Fabric's native JSON
═══════════════════════════════════════ */
let savedLayout = @json($template->layout);

if (savedLayout && savedLayout.fabricJSON) {
    // New format — full Fabric JSON (includes images correctly)
    canvas.loadFromJSON(savedLayout.fabricJSON, function() {
        canvas.renderAll();
        saveHistory();
    });
} else if (savedLayout && savedLayout.objects) {
    // Old format — manual objects (backward compatible)
    savedLayout.objects.forEach(obj => {
        if (obj.type === 'text') {
            canvas.add(new fabric.Text(obj.key || obj.text || '', {
                left: obj.x, top: obj.y,
                fontSize: obj.fontSize || 20,
                fill: obj.fill || '#0d0d0d',
                fontFamily: obj.fontFamily || 'DM Sans, sans-serif',
                fontWeight: obj.fontWeight || 'normal'
            }));
        }
        if (obj.type === 'image' && obj.src) {
            const imgUrl = (obj.srcType === 'base64' || obj.src.startsWith('data:'))
                ? obj.src
                : '/storage/templates/' + obj.src;
            fabric.Image.fromURL(imgUrl, function(img) {
                img.set({
                    left: obj.x, top: obj.y,
                    scaleX: obj.scaleX || 1,
                    scaleY: obj.scaleY || 1,
                    angle: obj.angle || 0,
                    crossOrigin: 'anonymous'
                });
                canvas.add(img);
                canvas.renderAll();
            });
        }
    });
}

/* ═══════════════════════════════════════
   GRID SNAP
═══════════════════════════════════════ */
const grid = 10;
canvas.on('object:moving', function(opt) {
    opt.target.set({
        left: Math.round(opt.target.left / grid) * grid,
        top:  Math.round(opt.target.top  / grid) * grid
    });
});

/* ═══════════════════════════════════════
   HISTORY
═══════════════════════════════════════ */
let history = [];
let historyStep = -1;
let isUndoRedo = false;

function saveHistory() {
    if (isUndoRedo) return;
    historyStep++;
    history = history.slice(0, historyStep);
    history.push(JSON.stringify(canvas.toJSON(['base64src'])));
}

canvas.on('object:added',    saveHistory);
canvas.on('object:modified', saveHistory);
canvas.on('object:removed',  saveHistory);

function undo() {
    if (historyStep > 0) {
        isUndoRedo = true;
        historyStep--;
        canvas.loadFromJSON(history[historyStep], () => {
            canvas.renderAll();
            isUndoRedo = false;
        });
    }
}
function redo() {
    if (historyStep < history.length - 1) {
        isUndoRedo = true;
        historyStep++;
        canvas.loadFromJSON(history[historyStep], () => {
            canvas.renderAll();
            isUndoRedo = false;
        });
    }
}

/* ═══════════════════════════════════════
   ADD DYNAMIC FIELD TEXT
═══════════════════════════════════════ */
function addField(key) {
    const t = new fabric.Text(key, {
        left: 100, top: 100,
        fontSize: 20,
        fontFamily: document.getElementById('fontFamily').value || 'DM Sans, sans-serif',
        fill: '#0d0d0d'
    });
    canvas.add(t);
    canvas.setActiveObject(t);
}

/* ═══════════════════════════════════════
   SPECIAL ELEMENTS
═══════════════════════════════════════ */
function addQRCode() {
    const rect = new fabric.Rect({
        left: 0, top: 0,
        width: 80, height: 80,
        fill: '#f5f5f5',
        stroke: '#ccc',
        strokeWidth: 1,
        rx: 4, ry: 4
    });
    const label = new fabric.Text('@{{qr_code}}', {
        left: 10, top: 30,
        fontSize: 8, fill: '#888'
    });
    const group = new fabric.Group([rect, label], { left: 900, top: 620 });
    canvas.add(group);
    canvas.setActiveObject(group);
}

function addSealPlaceholder() {
    const circle = new fabric.Circle({
        left: 0, top: 0, radius: 45,
        fill: 'transparent',
        stroke: '#c0392b', strokeWidth: 2,
        strokeDashArray: [5, 3]
    });
    const label = new fabric.Text('SEAL', {
        left: 18, top: 36,
        fontSize: 11, fill: '#c0392b',
        fontFamily: 'Cinzel, serif', fontWeight: '700'
    });
    const group = new fabric.Group([circle, label], { left: 820, top: 640 });
    canvas.add(group);
}

function addSignatureLine() {
    const line = new fabric.Line([0, 0, 200, 0], {
        left: 100, top: 700,
        stroke: '#0d0d0d', strokeWidth: 1
    });
    const label = new fabric.Text('Instructor Signature', {
        left: 130, top: 706,
        fontSize: 10, fill: '#888'
    });
    canvas.add(line);
    canvas.add(label);
}

/* ═══════════════════════════════════════
   IMAGE UPLOAD — store as base64 in Fabric JSON
═══════════════════════════════════════ */
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(f) {
        const base64 = f.target.result;
        fabric.Image.fromURL(base64, function(img) {
            // Scale down large images to fit canvas
            const maxW = 400, maxH = 400;
            let scaleX = 1, scaleY = 1;
            if (img.width  > maxW) scaleX = maxW / img.width;
            if (img.height > maxH) scaleY = maxH / img.height;
            const scale = Math.min(scaleX, scaleY);

            img.set({
                left: 200, top: 200,
                scaleX: scale, scaleY: scale,
                crossOrigin: 'anonymous'
            });
            // Store base64 as custom property so Fabric serializes it
            img.base64src = base64;
            canvas.add(img);
            canvas.setActiveObject(img);
            canvas.renderAll();
        });
    };
    reader.readAsDataURL(file);
    // Reset so same file can be re-uploaded
    e.target.value = '';
});

/* ═══════════════════════════════════════
   STYLE
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
    } else if (obj.type === 'group') {
        obj.getObjects().forEach(child => {
            if (child.type === 'text' || child.type === 'i-text') child.set(updates);
        });
    }
    canvas.renderAll();
    saveHistory();
}

function toggleBold() {
    const obj = canvas.getActiveObject();
    if (obj && (obj.type === 'text' || obj.type === 'i-text')) {
        obj.set('fontWeight', obj.fontWeight === 'bold' ? 'normal' : 'bold');
        canvas.renderAll();
    }
}
function toggleItalic() {
    const obj = canvas.getActiveObject();
    if (obj && (obj.type === 'text' || obj.type === 'i-text')) {
        obj.set('fontStyle', obj.fontStyle === 'italic' ? 'normal' : 'italic');
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

/* ═══════════════════════════════════════
   CANVAS BG
═══════════════════════════════════════ */
function setCanvasBg(color) {
    canvas.setBackgroundColor(color, canvas.renderAll.bind(canvas));
}

/* ═══════════════════════════════════════
   BELT SWATCH
═══════════════════════════════════════ */
function selectBelt(el, color) {
    document.querySelectorAll('.belt-swatch').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    const obj = canvas.getActiveObject();
    if (obj) { obj.set('stroke', color); canvas.renderAll(); }
}

/* ═══════════════════════════════════════
   POSITION
═══════════════════════════════════════ */
function applyPosition() {
    const obj = canvas.getActiveObject();
    if (!obj) return;
    const x = parseInt(document.getElementById('objX').value);
    const y = parseInt(document.getElementById('objY').value);
    if (!isNaN(x)) obj.set('left', x);
    if (!isNaN(y)) obj.set('top',  y);
    obj.setCoords();
    canvas.renderAll();
}
function setOpacity(val) {
    const obj = canvas.getActiveObject();
    if (obj) { obj.set('opacity', parseFloat(val)); canvas.renderAll(); }
}

canvas.on('selection:created', syncProps);
canvas.on('selection:updated', syncProps);
function syncProps() {
    const obj = canvas.getActiveObject();
    if (!obj) return;
    document.getElementById('objX').value       = Math.round(obj.left || 0);
    document.getElementById('objY').value       = Math.round(obj.top  || 0);
    document.getElementById('objOpacity').value = obj.opacity ?? 1;
    if (obj.type === 'text' || obj.type === 'i-text') {
        if (obj.fontSize) document.getElementById('fontSize').value = obj.fontSize;
        if (obj.fill)     document.getElementById('fontColor').value = obj.fill;
    }
}

/* ═══════════════════════════════════════
   GROUP / LAYER / LOCK
═══════════════════════════════════════ */
function groupObjects() {
    const sel = canvas.getActiveObject();
    if (sel && sel.type === 'activeSelection') { sel.toGroup(); canvas.renderAll(); }
}
function ungroupObjects() {
    const obj = canvas.getActiveObject();
    if (obj && obj.type === 'group') { obj.toActiveSelection(); canvas.renderAll(); }
}
function bringFront() { const o = canvas.getActiveObject(); if(o) canvas.bringToFront(o); }
function sendBack()   { const o = canvas.getActiveObject(); if(o) canvas.sendToBack(o); }
function lockObject() {
    const obj = canvas.getActiveObject();
    if (!obj) return;
    obj.set({ selectable: false, evented: false, opacity: 0.5 });
    canvas.discardActiveObject();
    canvas.renderAll();
}
function unlockAll() {
    canvas.getObjects().forEach(o => o.set({ selectable: true, evented: true, opacity: 1 }));
    canvas.renderAll();
}
function deleteObj() { const o = canvas.getActiveObject(); if(o) canvas.remove(o); }
function duplicate() {
    const obj = canvas.getActiveObject();
    if (obj) {
        obj.clone(function(clone) {
            clone.set({ left: obj.left + 20, top: obj.top + 20 });
            if (obj.base64src) clone.base64src = obj.base64src;
            canvas.add(clone);
            canvas.setActiveObject(clone);
        });
    }
}

/* ═══════════════════════════════════════
   KEYBOARD
═══════════════════════════════════════ */
document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    const obj = canvas.getActiveObject();
    if (!obj) return;
    if (e.key === 'Delete' || e.key === 'Backspace') { canvas.remove(obj); return; }
    if (e.key === 'd' && e.ctrlKey) { e.preventDefault(); duplicate(); return; }
    if (e.key === 'z' && e.ctrlKey) { e.preventDefault(); undo(); return; }
    if (e.key === 'y' && e.ctrlKey) { e.preventDefault(); redo(); return; }
    const nudge = e.shiftKey ? 10 : 2;
    switch (e.key) {
        case 'ArrowUp':    obj.top  -= nudge; break;
        case 'ArrowDown':  obj.top  += nudge; break;
        case 'ArrowLeft':  obj.left -= nudge; break;
        case 'ArrowRight': obj.left += nudge; break;
    }
    obj.setCoords();
    canvas.renderAll();
});

/* ═══════════════════════════════════════
   ZOOM
═══════════════════════════════════════ */
let zoom = 1;
function updateZoomLabel() {
    document.getElementById('zoomLabel').textContent = Math.round(zoom * 100) + '%';
}
function zoomIn()    { zoom = Math.min(zoom + 0.1, 3);   canvas.setZoom(zoom); updateZoomLabel(); }
function zoomOut()   { zoom = Math.max(zoom - 0.1, 0.2); canvas.setZoom(zoom); updateZoomLabel(); }
function resetZoom() { zoom = 1; canvas.setZoom(1); canvas.absolutePan({ x: 0, y: 0 }); updateZoomLabel(); }

canvas.on('mouse:wheel', function(opt) {
    const delta = opt.e.deltaY;
    zoom = canvas.getZoom();
    zoom *= 0.999 ** delta;
    zoom = Math.min(Math.max(zoom, 0.2), 3);
    canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
    document.getElementById('zoomLabel').textContent = Math.round(zoom * 100) + '%';
    opt.e.preventDefault();
    opt.e.stopPropagation();
});

/* ═══════════════════════════════════════
   EXPORT
═══════════════════════════════════════ */
function exportPNG() {
    const dataURL = canvas.toDataURL({ format: 'png', quality: 1, multiplier: 2 });
    const link = document.createElement('a');
    link.href = dataURL;
    link.download = '{{ Str::slug($template->name) }}-certificate.png';
    link.click();
}

/* ═══════════════════════════════════════
   PREVIEW
═══════════════════════════════════════ */
function previewCert() {
    const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
    Swal.fire({
        title: 'Certificate Preview',
        html: `<img src="${dataURL}" style="width:100%;border-radius:4px;">`,
        width: 900,
        background: '#13151a',
        color: '#e8e6e1',
        confirmButtonColor: '#c0392b',
        confirmButtonText: 'Close'
    });
}

/* ═══════════════════════════════════════
   SAVE — use Fabric's toJSON to capture images
═══════════════════════════════════════ */
function saveLayout() {
    // toJSON with custom properties — this correctly serializes images as base64
    const fabricJSON = canvas.toJSON(['base64src']);

    Swal.fire({
        title: 'Saving…',
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
            layout: {
                fabricJSON: fabricJSON   // ← full Fabric JSON including images
            }
        })
    })
    .then(res => res.json())
    .then(() => Swal.fire({
        icon: 'success',
        title: 'Saved!',
        text: 'Template layout saved.',
        background: '#13151a',
        color: '#e8e6e1',
        confirmButtonColor: '#c0392b'
    }))
    .catch(() => Swal.fire({
        icon: 'error',
        title: 'Save Failed',
        text: 'Could not save the layout.',
        background: '#13151a',
        color: '#e8e6e1'
    }));
}
</script>

</body>
</html>
