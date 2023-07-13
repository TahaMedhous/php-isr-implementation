function playAudioMaster(url, id, icon) {
    var audio = document.getElementById(id);
    let iconClass = document.getElementById(icon).className;
    if (audio.paused) {
        if (audio.src != url) {
            audio.src = url;
        }
        audio.play();
        const removed = iconClass.replace("fa-play", "fa-pause");
        document.getElementById(icon).className = removed;
    } else {
        if (audio.readyState == 0) {
            return;
        }

        if (audio.src != url) {
            audio.src = url;
            audio.play();
            const removed = iconClass.replace("fa-play", "fa-pause");
            document.getElementById(icon).className = removed;
            RemovePause(icon);
            return;
        } else {
            audio.pause();
            const removed = iconClass.replace("fa-pause", "fa-play");
            document.getElementById(icon).className = removed;
        }
    }
}

function RemovePause(excluded) {
    let pauseIcon = document.getElementsByClassName("fa-pause");
    for (let i = 0; i < pauseIcon.length; i++) {
        if (pauseIcon[i].id != excluded) {
            pauseIcon[i].className = pauseIcon[i].className.replace(
                "fa-pause",
                "fa-play"
            );
        }
    }
}

function RemovePauseIconAfterAudioEnded() {
    let pauseIcon = document.getElementsByClassName("fa-pause");
    let audio = document.getElementById("audioPlayer");
    audio.addEventListener("ended", function () {
        audio.src = "unloaded";

        for (let i = 0; i < pauseIcon.length; i++) {
            pauseIcon[i].className = pauseIcon[i].className.replace(
                "fa-pause",
                "fa-play"
            );
        }
    });
}

RemovePauseIconAfterAudioEnded();
