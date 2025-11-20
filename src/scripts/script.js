const fileInput = document.getElementById('fileInput1');

document.getElementById("search-for-titles").addEventListener("submit", searchSubmit);
document.getElementById("select-ads-order").addEventListener("change", selectionSubmit);

function genRandomName() {
    return Date.now().toString(36)
    + Math.random().toString(36).substring(2,5);
}

function searchSubmit(event) {
    const searchInput = document.getElementById("search_ads");
    
    if (searchInput.value.trim() == "") {
        event.preventDefault();
        return;
    };

    searchInput.value = searchInput.value.replace(/\s{2,}/g, " ").trim();
}

function selectionSubmit(event) {
    // console.log(event.target.value);
    event.target.form.submit();
    // fetch("db.php", {
    //     method: "POST",
    //     headers: {
    //         "Content-Type": "application/json"
    //     },
    //     body: JSON.stringify({
    //             e:"q",
    //             a: selec.value
    //         }
    //     )
    // })
    // .then((response) => response.text())
    // .then((response) => console.log(response))
}
//     const file = fileInput.files[0]; // Get the selected file
//     const reader = new FileReader();
//     const formData = new FormData();
    
//     if (!file) {
//         alert('Please select a file to upload.');
//         return;
//     }

//     reader.onload = async (e) => {
//         formData.append("file", e.target.result)
//         try {
//             const response = await fetch('http://localhost/teste/files', {
//                 method: 'POST',
//                 headers: {
//                     "Content-Type": "image/png"
//                 },
//                 body: formData,
//             });

//             if (response.ok) {
//                 const result = await response.arrayBuffer();
//                 alert('File uploaded successfully: ' + result.message);
//             } else {
//                 alert('Failed to upload file. Status: ' + response.status);
//             }
//         } catch (error) {
//             console.error('Error uploading file:', error);
//             alert('An error occurred while uploading the file.');
//         }
//     }

//     reader.readAsArrayBuffer(file);
    
// })
// // const upfile = document.getElementById("file-upload");
// // const img = document.querySelector("img");

// // upfile.addEventListener("change", async (event) => {
// //     const file = event.target.files[0]

// //     const formd = new FormData()
// //     formd.append('file', file);

// //     const response = await fetch('./files', {
// //         method: 'POST',
// //         headers: {
// //             "Content-Type": "image/png"
// //         },
// //         body: formd,
// //     })

// //     console.log(formd)
//     // const im = await fetch(file.name).then(res => res.arrayBuffer());

//     // await fetch("/", {
//     //     method: "POST",
//     //     headers: {
//     //         "Content-Type": "image/png"
//     //     },
//     //     body: im
//     // });
//     // console.log(event.target.files)
//     // const reader = new FileReader();
//     // if (file) {
//     //     reader.onload = (e) => {
//     //         fetch("/", {
//     //             method: "POST",
//     //             headers: {
//     //                 "Content-Type":"image/png"
//     //             },
//     //             body: e.target.result
//     //         })
//     //         .then(data => console.log(data))
//     //         .catch(error => console.error(error))
//     //         console.log(e.target.result);
//     //     }
//     //     reader.readAsArrayBuffer(file);
//     // }
// // })
