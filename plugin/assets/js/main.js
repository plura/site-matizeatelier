// Matize plugin — JS entry point

import { mtzInitForm } from './form.js';

document.querySelectorAll( '[data-mtz-form]' ).forEach( mtzInitForm );
