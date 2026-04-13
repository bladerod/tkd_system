<!DOCTYPE html>
<html>
<head>
    <title>Ultimate Template Editor</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
@vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add Simple-Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {margin:0; font-family:Arial; }
        .toolbar {
            width:270px;
            background:#f4f4f4;
            padding:10px;
            overflow:auto;
            height:100vh;
        }
        canvas { border:1px solid #ccc; }
        button, input { width:100%; margin-bottom:6px; padding:6px; }
    </style>
</head>
<body>
    <!-- navbar -->
    @include("includes.navbar")
    <!-- Sidebar -->
    @include('includes.sidebar')
    <div class="flex ms-70 mt-5">
<div class="toolbar">

    <h3>Elements</h3>
    <button onclick="addText('student_name') ">Student Name</button>
    <button onclick="addText('belt_level')">Belt Level</button>
    <button onclick="addText('date_issue')">Date</button>

    <h3>Upload</h3>
    <input type="file" id="imageUpload">

    <h3>Text Style</h3>
    <input type="number" id="fontSize" placeholder="Font Size">
    <input type="color" id="fontColor">
    <button onclick="applyStyle()">Apply Style</button>

    <h3>History</h3>
    <button onclick="undo()">Undo</button>
    <button onclick="redo()">Redo</button>

    <h3>Group</h3>
    <button onclick="groupObjects()">Group</button>
    <button onclick="ungroupObjects()">Ungroup</button>

    <h3>Layer</h3>
    <button onclick="bringFront()">Bring Front</button>
    <button onclick="sendBack()">Send Back</button>

    <h3>Lock</h3>
    <button onclick="lockObject()">Lock</button>
    <button onclick="unlockAll()">Unlock All</button>

    <h3>Zoom</h3>
    <button onclick="zoomIn()">+</button>
    <button onclick="zoomOut()">-</button>

    <h3>Actions</h3>
    <button onclick="duplicate()">Duplicate</button>
    <button onclick="deleteObj()">Delete</button>
    <button onclick="exportPNG()">Export PNG</button>
    <button onclick="saveLayout()">💾 Save</button>

</div>

<canvas id="canvas" width="1123" height="794"></canvas>

    </div>

<script>
const canvas = new fabric.Canvas('canvas', { selection: true });

/* ================= LOAD FIX ================= */
let savedLayout = @json($template->layout);
if(typeof savedLayout === "string"){
    savedLayout = JSON.parse(savedLayout);
}

/* ================= GRID ================= */
const grid = 20;
canvas.on('object:moving', function(opt){
    opt.target.set({
        left: Math.round(opt.target.left / grid) * grid,
        top: Math.round(opt.target.top / grid) * grid
    });
});

/* ================= HISTORY ================= */
let history = [];
let historyStep = -1;
let isUndoRedo = false;

function saveHistory(){
    if(isUndoRedo) return;
    historyStep++;
    history = history.slice(0, historyStep);
    history.push(JSON.stringify(canvas.toJSON()));
}

canvas.on('object:added', saveHistory);
canvas.on('object:modified', saveHistory);
canvas.on('object:removed', saveHistory);

function undo(){
    if(historyStep > 0){
        isUndoRedo = true;
        historyStep--;
        canvas.loadFromJSON(history[historyStep], ()=>{
            canvas.renderAll();
            isUndoRedo = false;
        });
    }
}

function redo(){
    if(historyStep < history.length - 1){
        isUndoRedo = true;
        historyStep++;
        canvas.loadFromJSON(history[historyStep], ()=>{
            canvas.renderAll();
            isUndoRedo = false;
        });
    }
}

/* ================= LOAD OBJECTS ================= */
if(savedLayout && savedLayout.objects){
    savedLayout.objects.forEach(obj=>{

        if(obj.type === 'text'){
            let t = new fabric.Text(obj.key,{
                left:obj.x,
                top:obj.y,
                fontSize:obj.fontSize,
                fill:obj.fill
            });
            canvas.add(t);
        }

        if(obj.type === 'image' && obj.src){
            fabric.Image.fromURL('/storage/templates/'+obj.src,function(img){
                img.set({
                    left:obj.x,
                    top:obj.y,
                    scaleX:obj.scaleX,
                    scaleY:obj.scaleY
                });
                canvas.add(img);
            });
        }

    });
}

/* ================= ADD TEXT ================= */
function addText(key){
    let t = new fabric.Text(key,{
        left:100,
        top:100,
        fontSize:20
    });
    canvas.add(t);
}

/* ================= IMAGE ================= */
document.getElementById('imageUpload').addEventListener('change',function(e){
    let reader = new FileReader();

    reader.onload = function(f){
        fabric.Image.fromURL(f.target.result,function(img){
            img.set({left:200, top:200, scaleX:0.3, scaleY:0.3});
            canvas.add(img);
        });
    };

    reader.readAsDataURL(e.target.files[0]);
});

/* ================= STYLE ================= */
function applyStyle(){
    let obj = canvas.getActiveObject();
    if(obj && obj.type === 'text'){
        obj.set({
            fontSize: parseInt(document.getElementById('fontSize').value) || obj.fontSize,
            fill: document.getElementById('fontColor').value
        });
        canvas.renderAll();
    }
}

/* ================= GROUP ================= */
function groupObjects(){
    let sel = canvas.getActiveObject();
    if(sel && sel.type === 'activeSelection'){
        sel.toGroup();
        canvas.renderAll();
    }
}

function ungroupObjects(){
    let obj = canvas.getActiveObject();
    if(obj && obj.type === 'group'){
        obj.toActiveSelection();
        canvas.renderAll();
    }
}

/* ================= LAYER ================= */
function bringFront(){
    let obj = canvas.getActiveObject();
    if(obj) canvas.bringToFront(obj);
}

function sendBack(){
    let obj = canvas.getActiveObject();
    if(obj) canvas.sendToBack(obj);
}

/* ================= LOCK ================= */
function lockObject(){
    let obj = canvas.getActiveObject();
    if(!obj) return;

    obj.set({
        selectable:false,
        evented:false,
        opacity:0.6
    });

    canvas.discardActiveObject();
    canvas.renderAll();
}

function unlockAll(){
    canvas.getObjects().forEach(obj=>{
        obj.set({
            selectable:true,
            evented:true,
            opacity:1
        });
    });
    canvas.renderAll();
}

/* ================= DELETE ================= */
function deleteObj(){
    let obj = canvas.getActiveObject();
    if(obj) canvas.remove(obj);
}

/* ================= DUPLICATE ================= */
function duplicate(){
    let obj = canvas.getActiveObject();
    if(obj){
        obj.clone(function(clone){
            clone.set({ left: obj.left + 20, top: obj.top + 20 });
            canvas.add(clone);
        });
    }
}

/* ================= KEYBOARD ================= */
document.addEventListener('keydown', function(e){
    let obj = canvas.getActiveObject();
    if(!obj) return;

    if(e.key === "Delete"){
        canvas.remove(obj);
    }

    if(e.key === "d" && e.ctrlKey){
        duplicate();
    }

    switch(e.key){
        case "ArrowUp": obj.top -= 2; break;
        case "ArrowDown": obj.top += 2; break;
        case "ArrowLeft": obj.left -= 2; break;
        case "ArrowRight": obj.left += 2; break;
    }

    canvas.renderAll();
});

/* ================= ZOOM ================= */
let zoom = 1;

function zoomIn(){
    zoom += 0.1;
    canvas.setZoom(zoom);
}

function zoomOut(){
    zoom -= 0.1;
    canvas.setZoom(zoom);
}

/* ================= EXPORT ================= */
function exportPNG(){
    let dataURL = canvas.toDataURL({
        format: 'png',
        quality: 1
    });

    let link = document.createElement('a');
    link.href = dataURL;
    link.download = 'certificate.png';
    link.click();
}

/* ================= SAVE ================= */
function saveLayout(){

    let objects = [];

    canvas.getObjects().forEach(obj=>{

        if(obj.type === 'text'){
            objects.push({
                type:'text',
                key: obj.text,
                x: obj.left,
                y: obj.top,
                fontSize: obj.fontSize,
                fill: obj.fill
            });
        }

        if(obj.type === 'image'){

            let src = '';

            if(obj._element && obj._element.src){
                if(obj._element.src.includes('storage')){
                    src = obj._element.src.replace(window.location.origin + '/storage/templates/','');
                }
            }

            objects.push({
                type:'image',
                x: obj.left,
                y: obj.top,
                scaleX: obj.scaleX,
                scaleY: obj.scaleY,
                src: src
            });
        }

    });

    fetch('/templates/{{ $template->id }}/save-layout', {
        method:'POST',
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type':'application/json',
            'Accept':'application/json'
        },
        body: JSON.stringify({
            layout: { objects: objects }
        })
    })
    .then(res => res.json())
    .then(()=> alert('✅ Saved!'))
    .catch(()=> alert('❌ Save failed'));
}
</script>

</body>
</html>
