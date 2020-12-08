<!DOCTYPE html>

<title>ParDb</title>

<style>
	html, body {
		height: 100%;

		margin: 0;
		padding: 0;
	}

	.main-column {
		height: 100%;

		display: flex;
		flex-direction: column;
	}

	.log-output {
		overflow: auto;
		flex-grow: 1;

		margin: 0;
		background: #111122;
		color: #fefefe;
		font-size: 16px;
		font-family: monospace;
	}

	.box {
		padding: 1rem;
	}
</style>

<div class="main-column">
	<section id="controls" class="box">
		<button type="button" id="update-btn">Update</button>
	</section>

	<div id="response-box" class="log-output box">
	</div>
</div>

<template id="log-record">
	<div class="log-record">
		<span class="message"></span>
		<pre class="context"></pre>
	</div>
</template>

<script>
	let updateBtn = document.getElementById("update-btn");
	let responseBox = document.getElementById("response-box");
	let waitingHandler = null;

	let logRecordTmpl = document.getElementById("log-record");
	let logRecordMessage = logRecordTmpl.content.querySelector(".message");
	let logRecordContext = logRecordTmpl.content.querySelector(".context");

	updateBtn.addEventListener('click', () => {
		update();

		updateBtn.disabled = true;
		responseBox.innerText = "Warte auf Antwort";

		let dots = 0;
		let direction = 1;
		waitingHandler = window.setInterval(() => {
			if (dots >= 3) {
				direction = -1;
			} else if (dots <= 0) {
				direction = 1;
			}

			dots += direction;
			responseBox.innerText = "Warte auf Antwort" + ".".repeat(dots);
		}, 500);
	});

	async function update() {
		let response = await fetch('update?log-output=true');
		let json = await response.json();

		responseBox.innerText = "";

		for (let record of json.logOutput) {
			logRecordMessage.innerText = record.message;

			if (record.context.length !== 0) {
				logRecordContext.innerText = JSON.stringify(record.context);
			} else {
				logRecordContext.innerText = "";
			}

			let clone = document.importNode(logRecordTmpl.content, true);
			responseBox.appendChild(clone);
		}

		window.clearInterval(waitingHandler);
		updateBtn.disabled = false;
	}
</script>