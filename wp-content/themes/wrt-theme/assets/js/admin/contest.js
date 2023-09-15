const WinnersList = () => {
	const init = () => {
		const winnersList = document.querySelector('#contest-winners');

		if (!winnersList) {
		return;
		}

		addEventListener();
		setTemplate();
		reOrder();
	};

	const addEventListener = () => {
		const addBtn = document.querySelector('#contest-winners + .add');
		const saveBtns = document.querySelectorAll('#contest-winners .save');
		const editBtns = document.querySelectorAll('#contest-winners .edit');
		const removeBtns = document.querySelectorAll('#contest-winners .remove');

		addBtn.addEventListener('click', addWinner);

		saveBtns.forEach((btn) => {
		btn.addEventListener('click', saveWinner);
		});

		editBtns.forEach((btn) => {
		btn.addEventListener('click', editWinner);
		});

		removeBtns.forEach((btn) => {
		btn.addEventListener('click', removeWinner);
		});
	};

	const setTemplate = () => {
		const winnersList = document.querySelector('#contest-winners');
		const template = winnersList.querySelector('li').cloneNode(true);

		template.querySelector('.winner').textContent = '';
		template.querySelector('input').value = '';

		if (template.classList.contains('display')) {
		template.classList.toggle('editable');
		template.classList.toggle('display');
		}

		winnersList.template = template;
	};

	const addWinner = (e) => {
		e.preventDefault();

		const winnersList = document.querySelector('#contest-winners');
		const template = winnersList.template.cloneNode(true);

		winnersList.appendChild(template);
		winnersList.querySelector('li:last-child input').focus();

		reOrder();
	};

	const saveWinner = (e) => {
		e.preventDefault();

		const winnerElement = e.target.closest('.repeatable');
		const winnerInput = winnerElement.querySelector('input');
		const winnerText = winnerInput.value;

		winnerElement.classList.toggle('editable');
		winnerElement.classList.toggle('display');
		winnerElement.querySelector('.winner').textContent = winnerText;
	};

	const editWinner = (e) => {
		e.preventDefault();

		const winnerElement = e.target.closest('.repeatable');

		winnerElement.classList.toggle('editable');
		winnerElement.classList.toggle('display');
		winnerElement.querySelector('input').focus();
	};

	const removeWinner = (e) => {
		e.preventDefault();

		const winnerElement = e.target.closest('li');
		winnerElement.remove();

		reOrder();
	};

	const reOrder = () => {
		const winnersList = document.querySelector('#contest-winners');
		const winners = winnersList.querySelectorAll('li');

		winners.forEach((winner, index) => {
		winner.setAttribute('data-order', index);
		winner.querySelector('input').setAttribute('name', `contest-winners[${index}]`);
		});
	};

	init();
};

export default WinnersList;
