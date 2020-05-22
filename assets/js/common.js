// Importer jQuery
import $ from 'jquery';
require('bootstrap');

document.querySelectorAll('[data-prototype]').forEach(function (element) {
    let newButton = document.createElement('button');
    newButton.setAttribute('type', 'button');
    newButton.classList.add('btn');
    newButton.classList.add('btn-success');
    newButton.innerHTML = "✚";

    let counter = element.querySelectorAll('li').length;

    element.querySelectorAll('[data-delete]').forEach(function (btn) {
        btn.onclick = function () {
            btn.parentNode.remove(btn);
        }
    });

    newButton.onclick = function () {
        let proto = element.getAttribute('data-prototype');
        proto = proto.replace(/__name__label__/g, counter);
        proto = proto.replace(/__name__/g, counter);
        counter++;
        let item = document.createElement('li');
        item.classList.add('d-flex');
        item.classList.add('align-items-center');

        let subForm = document.createElement('div');
        subForm.classList.add('flex-grow-1');
        subForm.innerHTML = proto;
        item.appendChild(subForm);

        let delButton = document.createElement('button');
        delButton.setAttribute('type', 'button');
        delButton.classList.add('btn');
        delButton.classList.add('btn-danger');
        delButton.classList.add('align-self-baseline');
        delButton.classList.add('ml-1');
        delButton.innerHTML = "⚊";
        delButton.onclick = function () {
            item.parentNode.removeChild(item);
        }

        item.appendChild(delButton);
        element.appendChild(item);
    };

    element.parentNode.appendChild(newButton);
});