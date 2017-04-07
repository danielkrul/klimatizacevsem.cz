<div id="visibilityContainer">
	<div class="center">
		<h2>Nakoupit <a href="index.php"><img class="close" src="./design/icons/close.png" width="25" /></a></h2>
		<form method="POST">
		<table>
			<tr>
				<td>Společnost: </td>
				<td>
					<input type="text" name="company" value="" autofocus />
				</td>
			</tr>

			<tr>
				<td>* Oslovení: </td>
				<td>
					<select name="gender">
						<option value="male">Pan</option>
						<option value="female">Paní</option>
					</select>
				</td>
			</tr>

			<tr>
				<td>Titul: </td>
				<td>
					<select name="title">
						<option value=""></option>
						<option value="Mgr. ">Mgr. </option>
						<option value="Bc. ">Bc. </option>
						<option value="Ing. ">Ing. </option>
						<option value="Mgr. ">Mgr. </option>
						<option value="Ph.D. ">Ph.D. </option>
						<option value="PhDr. ">PhDr. </option>
					</select>
				</td>
			</tr>

			<tr>
				<td>* Jméno a příjmení: </td>
				<td>
					<input type="text" name="name" value="" />
				</td>
			</tr>

			<tr>
				<td>* Ulice a číslo domu: </td>
				<td>
					<input type="text" name="street" />
				</td>
			</tr>

			<tr>
				<td>* PSČ a město: </td>
				<td>
					<input type="number" name="psc" />
				</td>
				<td>
					<input type="text" name="city" />
				</td>
			</tr>

			<tr>
				<td>* Telefon: </td>
				<td>
					<input type="number" name="phone" />
				</td>
			</tr>

			<tr>
				<td>* E-mail: </td>
				<td>
					<input type="email" name="email" />
				</td>
			</tr>

			<tr>
				<td><small>* - údaj nezbytný k objednávce </small></td>
			</tr>

			<tr>
				<td>
					<button class="add_button" name="buy_item_complete">Dokončit objednávku</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>