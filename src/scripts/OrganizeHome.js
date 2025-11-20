const a_up = document.getElementById("up");

console.log(new Date().toTimeString())

// let sec = 0
// const a = await new Promise((resolve) => {
//     setInterval(() => {
//         sec++;
//         if (sec == 2){
//             resolve("pronto")
//         };
//     },1000)
// })

let products_ids_loaded = [];

window.addEventListener("scroll", () => {
    if(scrollY > 50){
        a_up.style.display = "flex";
    } else {
        a_up.style.display = "none";
    }
})

function AddToHome(products_info_str) {
    const conteiner = document.getElementById("ads-home-ctn");
    const template = document.querySelector("template");
    const products_info = JSON.parse(products_info_str);
    const clone_a = template.content.cloneNode(true).childNodes[0];
    const [ img, info_ctn ] = clone_a.childNodes;
    const [ h2, h4 ] = info_ctn.childNodes;
    
    img.className = "image-uploaded";
    img.src = products_info.image_path;

    clone_a.setAttribute(
        "href",
        `products/${products_info.id}`
    );

    const options = {
        style: "currency",
        currency: "BRL",
        currencyDisplay: "symbol"
    };

    h2.innerHTML = products_info.titulo;

    if (products_info.preco != 0) {
        const toBRL = new Intl.NumberFormat("pt-BR", options);
        h4.textContent = toBRL.format(products_info.preco);
    } else {
        h4.textContent = "Grátis";
    }
    
    conteiner.append(clone_a);

    return {
        product_id: products_info.id
    }
}

function InsertAdsHome(products_obj){
    for(let i = 0; i < products_obj.length; i++){
        products_ids_loaded.push(
            AddToHome(products_obj[i]).product_id
        ) ;
    }
}

document.getElementById("up").addEventListener("click", () => {
    window.scroll({
        top: 0,
        behavior: "smooth"
    });
})

export default function StartHome(e, a = null) {
    return new Promise (async (resolve) => {
        await fetch("../controllers/search_and_post.php", {
            method:"POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                e,
                a: JSON.stringify([...a, products_ids_loaded.length > 0 ? JSON.stringify(products_ids_loaded) : null])
            })
        })
            .then(response => response.json())
            .then((re) => {
                const lista_produtos = JSON.parse(re);
                if(lista_produtos.length != 0){
                    InsertAdsHome(lista_produtos)
                } else if(!products_ids_loaded.length > 0) {
                    document.getElementById("not-found").style.display = "flex";
                }

                console.log(products_ids_loaded.length + "anúncios carregados")
                resolve(re)
            })
    })
}