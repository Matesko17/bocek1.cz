function lazy_image_loading() {
  document.querySelectorAll("span[data-lazy-image-loading]").forEach(function (span) {
    let thumbnail_url = span.getAttribute("data-lazy-image-loading");
    let image = span.querySelector("img");

    span.style.backgroundImage = "url('" + thumbnail_url + "')";

    if (image.complete) {
      span.classList.remove("loading");
    } else {
      span.classList.add("loading");

      image.addEventListener(
        "load",
        function () {
          span.classList.remove("loading");
        },
        { once: true }
      );
    }
  });
}

export default lazy_image_loading;
