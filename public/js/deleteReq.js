const deleteProduct = (productId) => {
    fetch (`/product/delete/${productId}`, {method: 'DELETE'}).then(res => {
        return res.json()
    }).then(jres => {
        alert(jres)
        window.location.reload()
    })
}