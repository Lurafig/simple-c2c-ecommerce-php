const comment_input = document.getElementById("comment-input");
const comments_ctn = document.getElementById("comments");
const comment_template = document.getElementById("comment_ctn_template");

export async function postComment(id){
    const data = {
        adid: id,
        content: comment_input.value
    };

    return fetch("../../controllers/post_comment.php",{
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                adid: id,
                content: comment_input.value
            })
        }
    ).then(response => {
        if(!response.ok){
            throw new Error("ndniuwn");
        }

        return response.json()
    });
}

export function insertUserInfos(ownerInfos){
    document.getElementById("owner-user-profile-link").href = `/profile/${ownerInfos.id}`;
    document.getElementById("ad-owner-username").textContent = ownerInfos.username;
}

export function putInfos(re){
    document.getElementById("title").textContent = re["title"];

    const a = new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL",
        currencyDisplay: "symbol"
    });

    document.getElementById("price").textContent = 
    !Number(re["product_value"]) 
    ? "Grátis" : a.format(re["product_value"]);
    
    const images_ctn = document.getElementById("images");
    const images = JSON.parse(
        '["' + re["image_path"]
        .replace(/,/g, '","')
        .replace(/\s+/g, "")
        + '"]');

    console.log(images);
    for(let image of images){
        const img_ctn = document.createElement("div");
        const img = document.createElement("img");
        img_ctn.id = "img-ctn";
        img.src = image;
        img.className = "ad_image";
        img_ctn.appendChild(img);
        images_ctn.append(img_ctn);
    }
    console.log(re)
}

export function InsertComment(comment){
    const clone = comment_template.content.cloneNode(true);

    const comment_ctn = clone.querySelector(".comment");
    const username_ctn = comment_ctn.querySelector(".username");
    const content_ctn = comment_ctn.querySelector(".content");

    username_ctn.textContent = comment.username;
    username_ctn.href = `/profile/${comment.user_id}`;
    content_ctn.textContent = comment.content;

    const separator = document.createElement("div");
    separator.className = "separator";

    comments_ctn.appendChild(comment_ctn);
    comments_ctn.append(separator);

    return 0;
}

export function putComments(commentsList){
    for(let comment of commentsList){
        InsertComment(comment);
    }
    return 0;
}

export default async function getAdInfo(id){
    return new Promise(resolve => {
        fetch("../../controllers/get_spec_ad.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id
            })
        }).then(response =>
            response.ok ? response.json() : {}
        )
        .then(res => resolve(res))
    })

}