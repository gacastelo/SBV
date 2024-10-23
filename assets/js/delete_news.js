document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-news');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            
            const newsId = this.getAttribute('data-id');
            if (confirm('Tem certeza que deseja deletar esta notícia?')) {
                fetch('../backend/delete_news.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: newsId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Notícia deletada com sucesso!');
                        location.reload();  // Atualiza a página
                    } else {
                        alert('Erro ao deletar a notícia: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
            }
        });
    });
});