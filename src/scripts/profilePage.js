const ads_ctn = document.getElementById("ads-list");
const ad_ctn_template = document.getElementById("ad_ctn_template");

function organizeAdInfos(ad){
    const clone = ad_ctn_template.content.cloneNode(true);
    
    const main_ad_ctn = clone.querySelector(".main-ad-ctn")
    const ad_ctn = main_ad_ctn.querySelector(".ad-ctn");
    const title = ad_ctn.querySelector(".ad_title");
    const price = ad_ctn.querySelector(".ad-price");
    const image = ad_ctn.querySelector(".img-ctn");
    const deleteBttn = main_ad_ctn.querySelector(".delete");

    if(deleteBttn) {
        deleteBttn.id = ad.id;
        deleteBttn.addEventListener("click", removeAd);
    }

    const e = new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL",
        currencyDisplay: "symbol"
    });

    ad_ctn.href = `/products/${ad.id}`;
    image.src = ad.image;
    title.textContent = ad.title;
    price.textContent = ad.product_value > 0 ? e.format(ad.product_value) : "Grátis";

    ads_ctn.appendChild(main_ad_ctn);
}

function insertUserInfos(infos) {
    const username = document.getElementById("user-name");

    username.textContent = infos.username;
}

function insertAds(ads){
    for(let ad of ads){
        organizeAdInfos(ad);
    }
}

async function getUserInfos(id){
    return fetch(
        "../../controllers/get_user_infos.php",{
            method: "POST",
            body: JSON.stringify({
                id: id
            })
        }
    )
        .then(response => {
            if(!response.ok){
                throw new Error("errou");
            }
            return response.json();
        })
}

function removeAd(event) {
    fetch(
        "../../controllers/delete_ad.php",
        {
            method: "POST",
            body: JSON.stringify({
                id: event.target.id
            })
        }
    ).then((response) => {
        if(!response.ok){
            throw new Error("ndjei");
        }

        return response.json();
    }).then(
        response => {
            console.log(response);
            if(response.success){
                const parent = event.target.parentNode;
                parent.remove();
                if(ads_ctn.children.length == 1){
                    document.getElementById("not-found").style.display = "flex";
                }
            }
        })
}