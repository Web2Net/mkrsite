// ================= Apparatus ========================= // 
function theRotator_apparatus() {
    // Устанавливаем прозрачность всех картинок в 0
    $('div#rotator_apparatus ul li').css({opacity: 0.0});
 
    // Берем первую картинку и показываем ее (по пути включаем полную видимость)
    $('div#rotator_apparatus ul li:first').css({opacity: 1.0});
 
    // Вызываем функцию rotate для запуска слайдшоу, 5000 = смена картинок происходит раз в 5 секунд
    setInterval('rotate_apparatus()',3000);
}
 
function rotate_apparatus() {    
    // Берем первую картинку
    var current = ($('div#rotator_apparatus ul li.show_apparatus')?  $('div#rotator_apparatus ul li.show_apparatus') : $('div#rotator_apparatus ul li:first'));
 
    // Берем следующую картинку, когда дойдем до последней начинаем с начала
    var next = ((current.next().length) ? ((current.next().hasClass('show_apparatus')) ? $('div#rotator_apparatus ul li:first') :current.next()) : $('div#rotator_apparatus ul li:first'));    
 
    // Расскомментируйте, чтобы показвать картинки в случайном порядке
     //var sibs = current.siblings();
//     var rndNum = Math.floor(Math.random() * sibs.length );
//     var next = $( sibs[ rndNum ] );
 
    // Подключаем эффект растворения/затухания для показа картинок, css-класс show имеет больший z-index
    next.css({opacity: 0.0})
    .addClass('show_apparatus')
    .animate({opacity: 1.0}, 1000);
 
    // Прячем текущую картинку
    current.animate({opacity: 0.0}, 1000)
    .removeClass('show_apparatus');
};
 
$(document).ready(function() {        
    // Запускаем слайдшоу
    theRotator_apparatus();
});
// ================= /Apparatus ========================= // 
// ================= Hit ========================= // 
function theRotator_hit_mounth() {
    // Устанавливаем прозрачность всех картинок в 0
    $('div#rotator_hit_mounth div').css({opacity: 0.0});
 
    // Берем первую картинку и показываем ее (по пути включаем полную видимость)
    $('div#rotator_hit_mounth div:first').css({opacity: 1.0});
 
    // Вызываем функцию rotate для запуска слайдшоу, 5000 = смена картинок происходит раз в 5 секунд
    setInterval('rotate_hit_mounth()',1500);
}
 
function rotate_hit_mounth() {    
    // Берем первую картинку
    var current = ($('div#rotator_hit_mounth div.show_hit_mounth')?  $('div#rotator_hit_mounth div.show_hit_mounth') : $('div#rotator_hit_mounth div:first'));
 
    // Берем следующую картинку, когда дойдем до последней начинаем с начала
    var next = ((current.next().length) ? ((current.next().hasClass('show_hit_mounth')) ? $('div#rotator_hit_mounth div:first') :current.next()) : $('div#rotator_hit_mounth div:first'));    
 
    // Расскомментируйте, чтобы показвать картинки в случайном порядке
    // var sibs = current.siblings();
    // var rndNum = Math.floor(Math.random() * sibs.length );
    // var next = $( sibs[ rndNum ] );
 
    // Подключаем эффект растворения/затухания для показа картинок, css-класс show имеет больший z-index
    next.css({opacity: 0.0})
    .addClass('show_hit_mounth')
    .animate({opacity: 1.0}, 1000);
 
    // Прячем текущую картинку
    current.animate({opacity: 0.0}, 1000)
    .removeClass('show_hit_mounth');
};
 
$(document).ready(function() {        
    // Запускаем слайдшоу
    theRotator_hit_mounth();
});
// ================= /Hit ========================= // 
// ================= Award ========================= //
function theRotator_award() {
    // Устанавливаем прозрачность всех картинок в 0
    $('div#rotator_award ul li').css({opacity: 0.0});
 
    // Берем первую картинку и показываем ее (по пути включаем полную видимость)
    $('div#rotator_award ul li:first').css({opacity: 1.0});
 
    // Вызываем функцию rotate для запуска слайдшоу, 5000 = смена картинок происходит раз в 5 секунд
    setInterval('rotate_award()',2000);
}
 
function rotate_award() {    
    // Берем первую картинку
    var current = ($('div#rotator_award ul li.show_award')?  $('div#rotator_award ul li.show_award') : $('div#rotator_award ul li:first'));
 
    // Берем следующую картинку, когда дойдем до последней начинаем с начала
    var next = ((current.next().length) ? ((current.next().hasClass('show_award')) ? $('div#rotator_award ul li:first') :current.next()) : $('div#rotator_award ul li:first'));    
 
    // Расскомментируйте, чтобы показвать картинки в случайном порядке
    // var sibs = current.siblings();
    // var rndNum = Math.floor(Math.random() * sibs.length );
    // var next = $( sibs[ rndNum ] );
 
    // Подключаем эффект растворения/затухания для показа картинок, css-класс show имеет больший z-index
    next.css({opacity: 0.0})
    .addClass('show_award')
    .animate({opacity: 1.0}, 1000);
 
    // Прячем текущую картинку
    current.animate({opacity: 0.0}, 1000)
    .removeClass('show_award');
};
 
$(document).ready(function() {        
    // Запускаем слайдшоу
    theRotator_award();
});
// ================= /Award ========================= // 

