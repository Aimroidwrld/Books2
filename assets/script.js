document.addEventListener("DOMContentLoaded", function () {
    var searchBox = document.getElementById("searchBox");
    var suggestions = document.getElementById("titleSuggestions");

    if (!searchBox || !suggestions) {
        return;
    }

    searchBox.addEventListener("keyup", function () {
        var keywords = searchBox.value;

        if (keywords.length < 2) {
            suggestions.innerHTML = "";
            return;
        }

        suggestions.innerHTML = "<div class='p-2'>Searching...</div>";

        fetch("ajax-search.php?search=" + encodeURIComponent(keywords))
            .then(function (response) { return response.json(); })
            .then(function (data) {
                suggestions.innerHTML = "";
                data.forEach(function (book) {
                    var div = document.createElement("div");
                    div.className = "p-2 suggestion-item";
                    div.textContent = book.title;
                    div.addEventListener("click", function () {
                        searchBox.value = book.title;
                        suggestions.innerHTML = "";
                    });
                    suggestions.appendChild(div);
                });
            })
            .catch(function (err) {
                console.error(err);
                suggestions.innerHTML = "<div class='p-2 text-danger'>Error loading suggestions</div>";
            });
    });
});
