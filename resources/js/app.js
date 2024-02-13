require('./bootstrap');

import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

Livewire.start();

$(document).ready(function () {
  // show the alert
  setTimeout(function () {
    $('.alert').alert('close');
  }, 2000);
});
