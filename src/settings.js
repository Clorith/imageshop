import React from 'react';
import ReactDOM from 'react-dom';

import Settings from './Settings';

const settingsContainer = document.getElementById( 'imageshop-settings' );

if ( settingsContainer ) {
	ReactDOM.render(
		<React.StrictMode>
			<Settings/>
		</React.StrictMode>,
		settingsContainer
	);
}
