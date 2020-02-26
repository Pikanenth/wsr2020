var 
api = "http://wsr.ru/photos/api", 
client = {}, 
obj = { share: [] }, 
helper = {};

// Настройка заголовка авторизации
client.ajax = () => {
    let token = sessionStorage.getItem("token");
    $.ajaxSetup({ 
        headers: { 
            Authorization: "Bearer " + token 
        } 
    });
};

// Регистрация клиента
client.signup = () => {
    $.post(api + "/signup", $(".form-signup").serialize(), (data) => {
        helper.notify("success", "Регистрация прошла успешно.");
        $('.form-login [name="phone"]').val($('.form-signup [name="phone"]').val());
        $('.form-login [name="password"]').val($('.form-signup [name="password"]').val());
        client.login();
    }).fail((data) => {
        data = data.responseJSON;
        for(let i in data) helper.notify("danger", data[i]);
    });
};

// Авторизация клиента
client.login = () => {
    $.post(api + "/login", $(".form-login").serialize(), (data) => {
        sessionStorage.setItem("token", data.token);
        client.ajax();
        $(".navbar").fadeIn();
        client.photos();
    }).fail((data) => {
        data = data.responseJSON;
        for(let i in data) helper.notify("danger", data[i]);
    });
};

// Выход клиента
client.logout = () => {
    sessionStorage.removeItem("token");
    helper.page("auth");
    $(".navbar").fadeOut();
    $.post(api + "/logout", (data) => {
        console.log(data);
    }).fail((data) => {
        console.log(data);
    });
};

// Получение всех фотографий
client.photos = (user) => {
    obj.share = [];
    $(".photos_group").hide();
    helper.page("photos");
    $("#photos_model_view").html("");
    $.get(api + "/photo", (data) => {
        obj.photos = data;
        for(let i in data) {
            $("#photos_model_view").append(`<div class="col-lg-4 mb-3">
                <div class="card h-100" data-photo="${ data[i].id }" style="display: none;">
                    <img src="${ data[i].url }" style="max-height: 300px;" onclick="helper.addGroup(${ data[i].id });" class="card-img-top" alt="${ data[i].name }">
                    <div class="card-body mb-0">
                    <h5 class="card-title">${ data[i].name }</h5>
                        <button type="button" style="width: 108px; font-size: 13px;" onclick="helper.editPhoto(${ data[i].id }, '${ data[i].name }', '${ data[i].url }');" class="btn btn-primary btn-sm">Редактировать</button>
                        <button type="button" style="font-size: 13px;" onclick="helper.addGroup(${ data[i].id }, true);helper.page('users')" class="btn btn-primary btn-sm">Поделиться</button>
                        <button type="button" style="font-size: 13px;" onclick="helper.addGroup(${ data[i].id });" class="btn btn-warning btn-sm mt-1">Выбрать в группу</button>
                        <button type="button" style="font-size: 13px;" onclick="helper.addGroup(${ data[i].id }, true);client.groupDelete();" class="btn btn-danger btn-sm mt-1">Удалить</button>
                    </div>
                </div>
            </div>`);
            setTimeout(() => $(`[data-photo="${ data[i].id }"]`).fadeIn(), 500);
        }
        if(user) client.view(user);
    }).fail((data) => {
        data = data.responseJSON;
        for(let i in data) helper.notify("danger", data[i]);
    });
};

// Поиск пользователей
client.users = () => {
    if(!document.getElementById("user_search_value").value) return false;
    $.get(api + "/user", { search: document.getElementById("user_search_value").value }, (data) => {
        console.log(data);
        $("#user_search_view").html("");
        for(let i in data) $("#user_search_view").append(`<div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                <h5 class="card-title">${ data[i].first_name } ${ data[i].surname }</h5>
                <h6 class="card-subtitle mb-2 text-muted mb-4">${ data[i].phone }</h6>
                <p class="card-text text-center">Выберите действие</p>
                    <a href="javascript:void(0)" onclick="client.share(${ data[i].id })" class="btn btn-sm btn-success mb-1 btn-block">Поделиться фотографий</a>
                    <a href="javascript:void(0)" onclick="client.view(${ data[i].id })" class="btn btn-sm btn-primary btn-block">Просмотр доступных фотографий</a>
                </div>
            </div>
        </div>`);
    }).fail((data) => {
        data = data.responseJSON;
        for(let i in data) helper.notify("danger", data[i]);
    });
};

// Шаринг фотографий
client.share = (user) => {
    let arr = [];
    for(let i in obj.share) arr.push(i);
    if(!arr[0]) return helper.notify("danger", "Нету фотографий, для публикации.");
    $.post(api + "/user/" + user + "/share", { photos: arr }, (data) => {
        client.photos(user);
		helper.notify("success", "Вы успешно поделились фотографиями");
    }).fail((data) => {
        data = data.responseJSON;
        for(let i in data) helper.notify("danger", data[i]);
    });
};

// Смена страницы 
helper.page = (page) => {
    $(".pages .page").hide();
    $(".pages .page-" + page).fadeIn();
    if(page != "auth") {
        $(".page-" + page + " .jumbotron").css({ "max-height": "0px" });
        $(".page-" + page + " .jumbotron").animate({ "max-height": "300px" }, 400);
    }
};

// Редактирование фотографии
var img, canvas, ctx, base64;
helper.editPhoto = (id, name, url) => {
    img = new Image();
    img.crossOrigin = "anonymous";
    img.onload = helper.draw;
    img.src = url;
    $("#photo_edit .modal-body").html(`<div class="form-group"><label>Название</label><input type="text" id="photo_edit_name" class="form-control" placeholder="Название фотографии"></div><div class="form-group"><canvas id="canvas"></canvas></div>
    <label for="customRange2">Обрезать фотографию</label>
    <input type="range" class="custom-range" min="1" max="20" id="customRange2">
    <button class="btn btn-block btn-success" onclick="client.editPhoto(${ id });">Сохранить изменения</button>`);
    canvas = document.getElementById('canvas');
    ctx = canvas.getContext("2d");
    $("#photo_edit_name").val(name);
    $("#photo_edit").modal("show");
    // Обрезка изображения 
    document.getElementById("customRange2").oninput = function () {
        let val = document.getElementById("customRange2").value;
        val /= 10;
        helper.draw(val);
    };
};
client.editPhoto = function (id) {
    $.post(api + "/photo/" + id, { 
        "_method": "patch",
        "name": $("#photo_edit_name").val(),
        "photo": base64
    }, (data) => {
        client.photos();
        $("#photo_edit").modal("hide");
        helper.notify("success", "Фотография успешно обновлена");
    }).fail((data) => {
        data = data.responseJSON;
        for(let i in data) helper.notify("danger", data[i]);
    });
}

// Рисование изображение
helper.draw = function (scale) {
    canvas.width = 466;
    canvas.height = 300;
    if(scale) ctx.scale(scale, scale);
    ctx.drawImage(img, 0, 0);
    base64 = canvas.toDataURL();
};

// Уведомления
helper.notify = (status, message) => {
    let time = Date.now();
    $('.notify').append(`<div class="alert alert-${ status } shadow-sm" data-time="${ time }" role="alert">${ message }</div>`);
    setTimeout(() => {
        $('.notify [data-time="'+ time +'"]').animate({ "opacity": "0" }, function() {
            $('.notify [data-time="'+ time +'"]').remove();
        });
    }, 1000);
};

// Загрузка фотографии
document.getElementById("customFile").onchange = function () {
    let formData = new FormData();
    formData.append('photo', document.getElementById("customFile").files[0]);
    let token = sessionStorage.getItem("token");
    fetch(api + "/photo", {
        method: 'POST',
        body: formData,
        headers: {
            Authorization: "Bearer " + token
        }
    })
    .then((response) => response.json())
    .then((result) => {
        if(result.photo) return helper.notify("danger", result.photo);
        client.photos();
        helper.notify("success", "Фотография успешно загружена!");
        $("#photo_upload").modal("hide");
    })
}

// Инициализация
if(sessionStorage.getItem("token")) {
    client.ajax();$(".navbar").fadeIn();
    client.photos();
} else {
    helper.page("auth");
}

// Поиск пользователей 
document.getElementById("user_search_value").oninput = client.users;

// Добавление в группу 
helper.addGroup = function (id, one) {

    if(one) obj.share = [];

    let el = $('[data-photo="'+ id +'"]');
    if(el.hasClass('activephoto')) {
        el.removeClass('activephoto');
        delete obj.share[id];
    }
    else {
        obj.share[id] = id;
        el.addClass("activephoto");
    }

    let status = 0;
    for(let i in obj.share) {
        status++;
    }

    $("#photos_group_count").html(status);
    if(status) $('.photos_group').fadeIn();
    else $('.photos_group').fadeOut();
    

};

// Просмотр доступных фотографий
client.view = (user) => {
    $("#user_photos .row").html("");
    obj.photos.forEach(function(element) {
        if(element.users.includes(user)) $("#user_photos .row").append(`<div class="col-lg-6">
            <div class="card">
                <img src="${ element.url }" class="card-img" alt="${ element.name }">
                <div class="card-body">
                    <h5 class="card-title">${ element.name }</h5>
                </div>
            </div>
        </div>`);
    });
    $("#user_photos").modal("show");
}

// Груповое удаление фотографий 
client.groupDelete = function () {
    for(let i in obj.share) $.ajax({
        url: api + "/photo/" + i,
        method: "DELETE"
    });
    obj.share = [];
    $('.photos_group').fadeOut();
	helper.notify("success", "Фотографии успешно удалены!");
    setTimeout(() => client.photos(), 500);
};