var game = { interval: false }, obj = { platforms: [], interval: [] }, helper = {};

// Персонаж
obj.character = { player: "rick", width: 43, height: 60, x: 0, y: 0 };

// Платформа
obj.platform = { width: 100, height: 30 };

// Коллизия
helper.collision = function(a, b) {
    let x = false, y = false;
    if( (a.x + a.width >= b.x) && (a.x <= b.x + b.width) ) x = true;
    if( (a.y + a.height >= b.y) && (a.y <= b.y + b.height) ) y = true;
    if(x && y) return true;
    return false;
};

// Коллизия по расстоянию (для поиска платформ)
helper.collisionX = function(a, b) {
    if( (a.x + a.width >= b.x) && (a.x <= b.x + b.width) ) return true;
    return false;
};

// Инструкция
helper.instruction = function () {
    if($(".welcome-page .section-1 .instruction").hasClass("show")) {
        $(".welcome-page .section-1 .instruction").removeClass("show");
        $(".welcome-page .section-1 .instruction").stop().animate({ "opacity": "0" });
        $(".welcome-page .section-1 .main").stop().animate({ "opacity": "1" });
        $(".welcome-page .section-1 .main").addClass("show");
    } else {
        $(".welcome-page .section-1 .instruction").addClass("show");
        $(".welcome-page .section-1 .instruction").stop().animate({ "opacity": "1" });
        $(".welcome-page .section-1 .main").stop().animate({ "opacity": "0" });
        $(".welcome-page .section-1 .main").removeClass("show");
    }
    return true;
};

// Страницы
helper.pages = function (page) {
    $('.app .pages .page').hide(); $('.app .pages ' + page).fadeIn();
    obj.page = page;
};

helper.pages(".welcome-page");

// Таймер 
helper.timer = function(sec) {
    let 
    minutes = parseInt(sec / 60),
    seconds = parseInt(sec - (minutes * 60));
    if(minutes < 10) minutes = "0" + minutes;
    if(seconds < 10) seconds = "0" + seconds;
    return `${ minutes }:${ seconds }`;
}

// Рандомная генерация числа (для высоты)
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min; //The maximum is exclusive and the minimum is inclusive
}

// Звук 
obj.sound = true;
helper.sound = function() {
    if(obj.sound) {
        $('.sound').removeClass('active');
        obj.sound = false;
        document.getElementById("song").pause();
    } else {
        $('.sound').addClass('active');
        obj.sound = true;
        document.getElementById("song").play();
    }
    return true;
};

// key input listener
document.querySelector(".welcome-page .section-2 .form input").oninput = function(e) {
    if(!$(".welcome-page .section-2 .form input").val()) $(".welcome-page .section-2 .form button").attr("disabled", true);
    else $(".welcome-page .section-2 .form button").attr("disabled", false);
};

// Validation and go to Preroll
game.preparing = function () {
    if(!$(".welcome-page .section-2 .form input").val()) return false;
    obj.character.name = $(".welcome-page .section-2 .form input").val();
    helper.pages(".preroll-page");
    document.querySelector('video').play();
    obj.interval["preroll"] = setTimeout(() => {
        game.start();
    }, 10000);
};

// Character Choice
game.character = function(name) {
    obj.character.player = name;
    $('.welcome-page .section-1 .character').removeClass('active');
    $('.welcome-page .section-1 .character-' + name).addClass('active');
};

// Start game
game.start = function () {
    obj.key = null;
    obj.pause = false;
    game.clearIntervals();
    $('.pause').hide();
    $('.gamebox').html(`<div class="character character-${ obj.character.player }"></div>
    <!-- Полигоны -->
    <div class="polygons"></div>
    <!-- Злые рики -->
    <div class="evil_ricks"></div>`);
    // 
    obj.interval = [];
    obj.stats = {
        hp: 100,
        heal: 0
    };
    obj.timer = 0;
    obj.character.x = 0;
    obj.character.name = obj.character.name || "Default";
    obj.character.y = 0;
    obj.evils = [];
    game.platforms();
    obj.background = 0;
    if(obj.sound) document.getElementById("song").play();
    document.querySelector('video').pause();
    $('.evil_ricks').html("");
    // 
    helper.pages(".game-page");
    game.startIntervals();
};

// Event keys 
addEventListener("keydown", function(e) {
    e.key = e.key;
    if(obj.page == ".preroll-page" && e.key == " ") return game.start();
    if(obj.page == ".game-page") {
        if(e.key == "Escape" || e.key == " ") return game.pause(); 
        let run = ["a", "s", "d", "w", "A", "W", "S", "D", "в", "ф", "ц", "ы", "Ф", "Ы", "Ц", "В"];
        if(run.includes(e.key)) obj.key = e.key;
        console.log(e.key);
    }
});

addEventListener("keyup", function(e) {
    if(obj.page == ".game-page") obj.key = null;
});

// Генерация платформ
game.platforms = function () {
    let x = 0;
    obj.platforms = [];
    for(let i = 0; i <= 20; i++) {
        obj.platforms.push({
            width: obj.platform.width,
            height: obj.platform.height,
            x: x,
            y: getRandomInt(60, 120)
        });
        x += (80 + obj.platform.width);
    }
    $('.gamebox .polygons').html("");
    for(let i in obj.platforms) $('.gamebox .polygons').append(`<div class="polygon" data-polygon="${ i }" style="left: ${ obj.platforms[i].x }px; bottom: ${ obj.platforms[i].y }px"></div>`);
    return obj.platforms;
}

// Генерация пончиков
game.eats = function () {
    if($('.gamebox .polygons span').length > 3) return false;
    $('.gamebox .polygons .polygon')[getRandomInt(0, 20)].innerHTML = "<span></span>";
    return true;
};

// Генерация злодеев
game.evil = function () {
    if($('.gamebox .evil').length >= 30) return false;
    let random = getRandomInt(-800, 800);
    let x = obj.character.x + random;
    let transform = "transform: rotateY(0deg)", left = true;
    if(random < 0) { transform = "transform: rotateY(180deg)"; left = false; }
    obj.evils.push({ width: 43, height: 60, x: x, y: 0, left: left });
    $('.gamebox .evil_ricks').append(`<div data-evil="${ obj.evils.length - 1}" class="evil" style="left: ${ x }px; bottom: 0px; ${ transform }"></div>`);
    return true;
};

// Рендер
game.render = function () {
    
    // Key
    if(obj.key) obj.key = (obj.key).toLowerCase(); 
    if(obj.stats.hp <= 0 || obj.character.x >= 2840) return game.end();
    
    if(obj.key == "ф") obj.key = "a";
    if(obj.key == "в") obj.key = "d";
    if(obj.key == "ы") obj.key = "s";
    if(obj.key == "ц") obj.key = "w";

    // Stats
    game.stats();
    
    // player 
    if(obj.key == "a") $(".gamebox .character").addClass("run").css({ "transform": "rotateY(0deg)" });
    else if(obj.key == "d") $(".gamebox .character").addClass("run").css({ "transform": "rotateY(180deg)" });
    else $(".gamebox .character").removeClass("run").css({ "transform": "rotateY(0deg)" });
    
    // Если только ты не спрятался под землю 
    if(obj.character.y >= 0) {

        // position 
        if(obj.key == "a" && obj.character.x > 10) obj.character.x -= 10;
        else if(obj.key == "d") obj.character.x += 10;
        $(".gamebox .character").css("left", obj.character.x);

        // background position 
        if(obj.key == "d" && obj.character.x >= 350 && obj.background > -2200) obj.background -= 10;
        if(obj.key == "a" && obj.character.x >= 350 && obj.background < 0) obj.background += 10;
        $(".gamebox").css({ "margin-left": obj.background + "px" });
        $(".game-page").css({ "background-position-x": obj.background + "px" });
        

        // Падаем с платформ
        let jumpstatus = false;
        obj.platforms.forEach(function(element, index) {
            if(helper.collision(obj.character, element)) {
                jumpstatus = true;
                // Захват пончиков 
                if($('[data-polygon="'+ index +'"]').find("span")[0]) {
                    $('[data-polygon="'+ index +'"] span').remove();
                    obj.stats.heal += 1;
                    obj.stats.hp += 10;
                    $('.gamebox .character').css({ "filter": "invert(1)" });
                    $(".stats" ).css({ "background": "green" });
                    setTimeout(() => {
                        $(".stats").css({ "background": "" });
                        $('.gamebox .character').css({ "filter": "invert(0)" });
                    }, 500);
                }
            }
        });

        if(!jumpstatus && obj.character.y > 0) {
            obj.character.y = 0;
            return $(".gamebox .character").stop().animate({ "bottom": obj.character.y + "px" }, "linear");
        }

        // Прыжки на платформы
        jumpstatus = false;
        
        if(obj.character.y >= 0) obj.platforms.forEach(function(element) {
            if(helper.collisionX(obj.character, element) && obj.key == "w") {
                obj.character.y = element.y + 20;
                return $(".gamebox .character").stop().animate({ "bottom": obj.character.y + "px" }, "linear");
            }
        });

        if(obj.key == "s" && obj.character.y <= 0) {
            obj.character.y = -65;
            $(".gamebox .character").stop().animate({ "bottom": obj.character.y + "px" }, "linear");
        }
        else if(obj.key == "s" && obj.character.y > 40) {
            obj.character.y = 0; obj.key = null;
            $(".gamebox .character").css({ "bottom": obj.character.y + "px" }, "linear");
        }

    } else {
        if(obj.key == "w") {
            obj.character.y = 0; obj.key = null;
            $(".gamebox .character").stop().animate({ "bottom": obj.character.y + "px" }, "linear");
        }
    }
    // Передвижение злых риков.
    obj.evils.forEach(function(element, index) {
        if(!helper.collision(obj.character, element)) {
            if(element.left) element.x -= 3;
            else element.x += 3;
            $(`[data-evil="${ index }"]`).css("left", element.x);
        }
    });
};

// Статистика
game.stats = function () {
    $('name').html(obj.character.name);
    $('hp').html(obj.stats.hp);
    $('heat').html(obj.stats.heal);
    $('timer').html(helper.timer(obj.timer));
};

// Конец игры
game.end = function () {
    game.clearIntervals();
    document.getElementById("song").pause();
    $.get("/php/score.php", { name: obj.character.name, score: (1000 - obj.timer + obj.stats.heal * 10) }, (data) => {
        $('tbody').html(`<tr><td>Место</td><td>Игрок</td><td>Кол-во очков</td></tr>`);
        for(let i in data) $("tbody").append(`<tr><td>${ data[i].place }</td><td>${ data[i].name }</td><td>${ data[i].score }</td></tr>`);
        helper.pages(".finish-page");
    });
}

// интервалы
game.startIntervals = function () {
    // Время
    obj.interval["timer"] = setInterval(() => {
        obj.timer++;
        game.eats();
        game.evil();
    }, 1000);
    // Ходьба
    obj.interval["run"] = setInterval(() => {
        let run = ["a", "s", "d", "w", "в", "ф", "ц", "ы"];
        if(run.includes(obj.key)) obj.stats.hp -= 1; 
    }, 1000);
    // Столкновения с риками
    obj.interval["evils"] = setInterval(() => {
        obj.evils.forEach(function(element) {
            if(helper.collision(obj.character, element)) {
                obj.stats.hp -= 20;
                $(".stats" ).css({ "background": "red" });
                $('.gamebox .character').css({ "filter": "invert(1)" });
                setTimeout(() => {
                    $(".stats").css({ "background": "" });
                    $('.gamebox .character').css({ "filter": "invert(0)" });
                }, 500);
                // звук удара!
                if(obj.sound) document.getElementById("song_kill").play();
                return false;
            }
        });
    }, 1000);
    game.interval = setInterval(game.render, 30);
};
game.clearIntervals = function () {
    clearInterval(obj.interval["preroll"]);
    clearInterval(obj.interval["timer"]);
    clearInterval(obj.interval["evils"]);
    clearInterval(obj.interval["run"]);
    clearInterval(game.interval);
};

// Пауза 
obj.pause = false;
game.pause = function () {
    if(!obj.pause) {
        game.clearIntervals();
        obj.pause = true;
        $('.gamebox .character').css({ "animation": "none" });
        $('.gamebox .evil').css({ "animation": "none" });
        $('.pause').show();
    } else {
        obj.pause = false;
        game.startIntervals();
        $('.gamebox .character').css({ "animation": "hero .6s steps(4) infinite" });
        $('.gamebox .evil').css({ "animation": "hero .6s steps(4) infinite" });
        $('.pause').hide();
    }
};