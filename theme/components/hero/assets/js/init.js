document.addEventListener('DOMContentLoaded', () => {

	const paths = document.querySelectorAll('.hero__logo svg path');

	if (!paths.length) return;

	// Set each path's --path-length to its actual total length
	paths.forEach(path => {
		const length = path.getTotalLength();
		path.style.setProperty('--path-length', length);
		path.style.strokeDasharray = length;
		path.style.strokeDashoffset = length;
	});

	// Animate stroke-dashoffset to 0 (draw in)
	gsap.to(paths, {
		strokeDashoffset: 0,
		duration: 2,
		ease: 'power2.inOut',
		stagger: 0.1,
		delay: 0.3,
	});

});
