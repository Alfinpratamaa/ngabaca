import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard';

window.Alpine = Alpine;
Alpine.plugin(Clipboard);
Alpine.start();

import Swal from 'sweetalert2';
window.Swal = Swal;
jsx;

Livewire.start();
