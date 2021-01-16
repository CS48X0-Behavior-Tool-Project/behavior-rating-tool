<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CSS ORDER MATTERS -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Sticky Navigation</title>

</head>

<body>
	<div id="app">
		<v-app>
			<v-main>
				<div>
					<v-toolbar dense id="toolbar">
						<v-app-bar-nav-icon></v-app-bar-nav-icon>

						<v-toolbar-title>Virtual Wellness System</v-toolbar-title>

						<v-spacer></v-spacer>

						<v-btn icon>
							<v-icon>mdi-magnify</v-icon>
						</v-btn>

						<v-btn icon>
							<v-icon>mdi-heart</v-icon>
						</v-btn>

						<v-btn icon>
							<v-icon>mdi-dots-vertical</v-icon>
						</v-btn>
					</v-toolbar>
					<v-carousel hide-delimiters>
						<v-carousel-item
						v-for="(item,i) in items"
						:key="i"
						:src="item.src"
						></v-carousel-item>
					</v-carousel>
				</div>
				<div>
					<v-container class="grey lighten-5">
						<h2>Content One</h2>
						<p>
							Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem placeat quam nostrum itaque ut cum quasi at nam quas repellat, earum rem consectetur architecto sed iure, obcaecati ab veniam, laudantium illo. Autem ullam similique praesentium fuga sed quae cupiditate, minima corporis molestias quam, labore soluta et error veniam nihil tempore a officia laudantium minus voluptatibus aut, consequuntur sunt quis! Quae numquam sit possimus aperiam. Maxime libero numquam molestias, aliquam aut reprehenderit corporis ratione eaque maiores deserunt earum cupiditate distinctio explicabo non dolorum totam ipsam magni adipisci minus veritatis fuga. Dignissimos, magnam exercitationem reiciendis odit quibusdam sint quia fugit ducimus eum.
						</p>
					</v-container>
					<v-container class="grey lighten-5">
						<h3>Content Two</h3>
						<p>
							Lorem, ipsum dolor sit amet consectetur adipisicing elit. Culpa, ab! Voluptate eum repellendus repellat incidunt a enim, vero neque, cum reprehenderit, sunt assumenda eaque dignissimos atque eveniet unde. Nulla quam suscipit magni obcaecati est architecto quod maiores illum distinctio. Fuga vel, vitae nobis in at mollitia amet impedit, voluptas dolores, rerum cum assumenda sint asperiores temporibus. Quo, dicta dolor vitae harum labore culpa sunt voluptatum laudantium recusandae, ipsam iste. Eos, et. Aut aliquid earum, quas blanditiis expedita in quisquam facilis dolor reiciendis obcaecati, quo voluptatum libero, atque ut molestiae aspernatur minima et debitis totam provident eius harum. Quaerat, praesentium doloribus?</p>
					</v-container>
				</div>
			</v-main>
		</v-app>
	</div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
	<script type="module" src="/js/welcome_message.js">

	</script>
</body>

</html>
