const deleteProduct = (productId) => {
    fetch (`/product/${productId}`, {method: 'DELETE'}).then(res => {
        return res.json()
    }).then(jres => {
        alert(jres)
        window.location.reload()
    })
}