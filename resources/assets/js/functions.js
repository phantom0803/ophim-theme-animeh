$(document).ready(function () {
    $(".parent-menu").click(function () {
        $(".parent-menu").each(function () {
            $(this).removeClass("active");
        })
        $(".sub-menu-content").each(function () {
            $(this).removeClass("display-block");
        })
        $(this).addClass("active");
        let tabId = $(this).attr("bind").split("-")[1]
        $(`#tab-${tabId}`).addClass("display-block");
    })

    $("#rated").click(function () {
        $("#modal").css({ "display": "block", "position": "absolute", "visibility": "visible", "top": "400px", "left": "50%", "transition": "top 0.3s ease 0s", "transform": "translate(-50%, -50%)", "width": "100%" });
    })
    $("#close-modal-rated").click(function () {
        $("#modal").css({ "display": "block", "visibility": "hidden", "top": "0px", "transition": "top 0.3s ease 0s" })
    })

    $("#report_error").click(function () {
        if ($("#episode_error").css('display') != 'block') {
            $("#episode_error").css('display', 'block')
        } else {
            $("#episode_error").css('display', 'none')
        }
    })
    $("input#error_send").click(function () {
        console.log(123);
        let error_message = $("input[name=error_message]").val();
        fetch(ROUTE_REPORT_ERROR, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content')
            },
            body: JSON.stringify({
                message: error_message
            })
        });
        $.toast({
            heading: 'Thông báo',
            text: 'Phản hồi của bạn đã được gửi đi!',
            position: 'bottom-right',
            icon: 'info',
            loader: true,
            loaderBg: '#9EC600',
            bgColor: '#212121',
            textColor: 'white'
        })
        $("#episode_error").remove();
    })
    $("#toggle_trailer").click(function () {
        $("#modal-trailer").css({ "display": "block", "position": "absolute", "visibility": "visible", "top": "300px", "left": "50%", "transition": "top 0.3s ease 0s", "transform": "translate(-50%, -50%)", "width": "100%" });
    })
    $("#close-modal-trailer").click(function () {
        $("#modal-trailer").css({ "display": "block", "visibility": "hidden", "top": "0px", "transition": "top 0.3s ease 0s" })
    })
});

function clickEventDropDown(this_dropdown, icon_default = "Null") {
    var _name = this_dropdown.getAttribute("bind");
    var _dropdown_menu = document.getElementById(_name);
    if (!_dropdown_menu.style.display || _dropdown_menu.style.display === "none") {
        this_dropdown.innerHTML = `<span class="material-icons-round">highlight_off</span>`;
        if (icon_default !== "expand_more") {
            this_dropdown.style.backgroundColor = "#ab3e3e";
        }
        _dropdown_menu.style.display = "flex";
        setTimeout(function () {
            _dropdown_menu.style.transform = "scale(1)";
        }, 50)
    } else {
        _dropdown_menu.style = null;
        this_dropdown.style = null;
        this_dropdown.innerHTML = `<span class="material-icons-round">${icon_default}</span>`;
    }
}
