import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboar from '@ryangjchandler/alpine-clipboard';

Alpine.plugin(Clipboar);

Livewire.start();

import Swal from 'sweetalert2';
window.Swal = Swal;
