document.addEventListener("DOMContentLoaded", function () {
  let video = document.getElementById("js-footer-video-created-by");
  let isPlayingForward = false;
  let intervalRewind;

  if (typeof video === "undefined" || video === null) {
    return;
  }

  // Pause the video and set it to the first frame
  video.pause();
  video.currentTime = 0;

  // Add mouseover event listener
  video.addEventListener("mouseover", function () {
    // Play the video forward to the last frame
    isPlayingForward = true;

    clearInterval(intervalRewind);
    video.play();
  });

  // Add mouseout event listener
  video.addEventListener("mouseout", function () {
    // Play the video backward to the first frame
    isPlayingForward = false;

    video.pause();

    clearInterval(intervalRewind);

    intervalRewind = setInterval(function () {
      if (!isPlayingForward) {
        if (video.currentTime === 0) {
          clearInterval(intervalRewind);
          video.pause();
        } else {
          video.currentTime += -0.01;
        }
      }
    }, 10);
  });
});