const chooseimg = document.getElementById("file1");
const imagesform = document.getElementById("file");
const submitBttn = document.getElementById("submitBttn");
const priceInput = document.getElementById("price");
const titleArea = document.getElementById("title");
const charCountSpan = document.getElementById("char-count");
const ShowImagesDiv = document.getElementById("show-images-ctn");

const ad = document.getElementById("advice");

ShowImagesDiv.addEventListener("scroll", DefineArrowsDisplay)

function DefineArrowsDisplay(event) {
    const maxWidthImagesDiv = 
    document.getElementById("upload-images-ctn").scrollWidth;

    const imgW = 
    Number(window.getComputedStyle(document.querySelector(".upload-image-ctn")
    ).width.replace("px", ""));

    const ScrollL = event.target.scrollLeft
    
    const u = Number(window.getComputedStyle(ShowImagesDiv).width.replace("px", ""));

    const SetArrowDisplay = (Arrow = "Left" || "Right", Display) => {
        document.documentElement.style.setProperty(
            Arrow === "Left" ? "--arrow-left-display"
            : "--arrow-right-display"
            , Display
        )
    }

    const ArrowsDisplay = {
        bothBlock() {
            SetArrowDisplay("Left", "block");
            SetArrowDisplay("Right", "block");
        },
        leftNone() {
            SetArrowDisplay("Left", "none");
            SetArrowDisplay("Right", "block");
        },
        rightNone() {
            SetArrowDisplay("Left", "block");
            SetArrowDisplay("Right", "none");
        }
    }

    if (ScrollL >= maxWidthImagesDiv - u - imgW/2) {
        ArrowsDisplay.rightNone();
    } else if (
        ScrollL <= maxWidthImagesDiv
        && ScrollL > imgW/2
    ) {
        ArrowsDisplay.bothBlock();
    } else {
        ArrowsDisplay.leftNone();
    }
};

document.getElementById("upload-image-bttn").addEventListener("click", () => {
    uploaded_files_ids = [];
    chooseimg.click();
});

imagesform.addEventListener("submit", handleSubmit);

async function processFile(file) {
    const a = new FileReader();
    a.readAsArrayBuffer(file);

    a.onload = (event) => {
        let data = event.target.result;
        window.URL = window.URL || window.webkitURL;

        let blob = new Blob(
            [data],
            { type: file.type }
        );

        let i = window.URL.createObjectURL(blob);
        const u = new Image();
        u.src = i;

        u.onload = () => {
            let resized = resizeImage(u);
            let newinput = document.createElement("input");
            newinput.type = 'hidden';
            newinput.id = "file1"
            newinput.name = 'files[]';
            newinput.value = resized;
            
            imagesform.append(newinput);
            console.log("b");
        };
    }

    return new Promise((resolve) => {
        const interval = setInterval(() => {
            if(a.readyState == 2){
                clearInterval(interval);
                resolve(`${file.name} processado`);
            }
            return;
        }, 100);
    });
}

function readFiles(files) {
    return new Promise(async(resolve) => {
        for(let i = 0; i < files.length; i++) {
            const y = await processFile(files[i])
            console.log(y);
        }
        resolve("Imagens processadas");
    })
}

let ids = [];

chooseimg.addEventListener("change", (event) => {
    let exisinp = document.getElementsByName("files[]");

    while(exisinp.length != 0){
        console.log("a");
        imagesform.removeChild(exisinp[0])
    }

    ids = [];

    for(let i = 0; i < chooseimg.files.length; i++){
        ids.push(genRandID());
    };
    
    readFiles(event.target.files)
        .then((res) => {
            console.log(res);
            submitBttn.click();
        });
});

function resizeImage(img) {
    let canvas = document.createElement("canvas");

    max_width = 700;
    max_height = 700;

    let width = img.width;
    let height = img.height;

    if (width > height) {
        if (width > max_width) {
            //height *= max_width / width;
            height = Math.round(height *= max_width / width);
            width = max_width;
        }
    } else {
        if (height > max_height) {
            //width *= max_height / height;
            width = Math.round(width *= max_height / height);
            height = max_height;
        }
    }

    canvas.width = width;
    canvas.height = height;

    let ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0, width, height);

    // const link = document.createElement('a');
    // link.download = 'canvas-image.png'; // File name
    // link.href = canvas.toDataURL("image/png", 0.7); // Data URL
    // link.click(); // Trigger download

    return canvas.toDataURL("image/jpeg", 0.7);
}

let maxChars = titleArea.getAttribute("maxlength");

document.getElementById("maxlen").textContent = maxChars;
titleArea.addEventListener("input", updateCharCount);

function updateCharCount(event) {
    let currLenCount = event.target.value.length;

    if (currLenCount == maxChars) {
        charCountSpan.style.color = "red";
    } else if (currLenCount > 230) {
        charCountSpan.style.color = "orange";
    } else if (currLenCount > 200) {
        charCountSpan.style.color = "yellow"
    } else {
        charCountSpan.style.color = "white"
    }

    document.getElementById("curr-text-len").textContent = currLenCount;
};

priceInput.addEventListener("input", (event) => {
    const inputValue = event.target.value;

    if(/[.,](.\d{2})/.test(inputValue)) {
        event.target.value = 
        inputValue.replace(
            /[.,](\d{2,})/,
            inputValue.match(/[.,](\d{2})/)[0]
        );
    }
});

priceInput.addEventListener("beforeinput", PreventingInput);

function PreventingInput(event) {
    let haveD = false

    if (/[,.]/.test(event.target.value)) haveD = true;

    if (!
        (event.inputType == "deleteContentBackward"
        || (/[.,]/.test(event.data)
            && !haveD)
        || (/\d/.test(event.data))
        )
    ) event.preventDefault();
}

function genRandID() {
    return Date.now().toString(36) + 
    Math.random().toString(36).substring(2, 10);
};

let uploaded_files_ids = [];

function handleSubmit(event) {
    const form = event.currentTarget;
    const url = new URL(form.action);
    const formData = new FormData(form);

    formData.append("func", "a");
    formData.append("param", JSON.stringify(ids));
    
    const fetchOptions = {
        method: form.method,
        body: formData
    };

    fetch(url, fetchOptions)
        .then(response => 
            response.ok ? response.json() : {}
        )
        .catch(() => {
            for(let i = 0; i < ids.length; i++){
                const path = `../files/${ids[i]}.png`;
                uploaded_files_ids.push(path);
            }
        })
        .then((response) => {
            if(response.err_code) {
                ids = [];
                ad.textContent = response.err_message;
                ad.style.display = "block";
                return;
            } else {
                ad.style.display = "none";
            };

            const imagesCtnClass = document.getElementsByClassName("uploaded-image-to-post");

            for(let i = 0; i < ids.length; i++){
                const path = `../files/${ids[i]}.png`;
                imagesCtnClass[i].src = path;
                imagesCtnClass[i].style.display = "block";
                uploaded_files_ids.push(path);
            };
        });

    event.preventDefault();
};

function removeUploadedFiles() {
    fetch("controllers/upload.php", {
            method:"post",
            body: JSON.stringify({
                "func": "r",
                "param": JSON.stringify(uploaded_files_ids)
            })
        }
    )
    .then(response => response.json())
    .then(response => {
        console.log(response)
    })

    return;
};

async function sendAd(images, title, price){
    if (chooseimg.files.length == 0 || chooseimg.files.length > 5) {
        return {
            ok: false,
            message: "Você deve adicionar pelo menos uma imagem"
        };
    }

    if (title.trim() === ""){
        return {
            ok: false,
            message: "Insira um titulo valido"
        }
    }

    if (price.trim() === ""){
        return {
            ok: false,
            message: "Insira um preço valido"
        }
    }

    const [URL, options] = [
        "controllers/search_and_post.php", {
            method: "POST",
            body: JSON.stringify({
                "e": "p",
                "a": JSON.stringify([
                    images,
                    title,
                    Number(price)
                ])
            })
        }
    ];

    const response = await new Promise((resolve) => {
        fetch(URL, options)
            .then(response => response.text())
            .then(response => resolve(JSON.parse(response)))
    })

    if(response.err_code) {
        return {
            ok: false,
            message: response.err_message
        }
    }

    return {
        ok: true,
        message: "Anúncio postado com sucesso"
    };
}

window.addEventListener("unload", removeUploadedFiles);

const [cancel_bttn, send_bttn] = [
    document.getElementById("cancel_bttn"),
    document.getElementById("send_bttn")
];

async function testSendAd(event) {
    console.log(chooseimg.files);

    const sendResponse = await new Promise(async (resolve) => {
        resolve (sendAd(
            JSON.stringify(uploaded_files_ids),
            titleArea.value,
            priceInput.value.replace(",", ".")
        ))
    });

    console.log("inednsn");
    console.log(sendResponse);
    
    if(sendResponse.ok) {
        console.log("aaa");
        ad.style.display = "none";
        [
            titleArea,
            priceInput,
            event.target,
            cancel_bttn
        ].forEach(a => a.disabled = true);
        return 1;
    } else {
        ad.style.display = "block";
        ad.textContent = sendResponse.message;
        return 0;
    }
}

send_bttn.addEventListener("click", async (event) => {
    const resp = testSendAd(event);

    if (await resp){
        console.log("ok")
        window.removeEventListener("unload", removeUploadedFiles);
        window.location.href = "home";
    }
});

cancel_bttn.addEventListener("click", () => history.back());

// const y = crypto.randomUUID();
// console.log(y);