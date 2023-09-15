<?php
/**
 * Most popular template.
 *
 * Template Name: ENG-5.13 Most Popular
 *
 * @package WRTTheme
 */

get_header(); ?>

<style>
	body {
		background-color: #FAFAFA;
	}

	.stripe-background {
		background-image: linear-gradient(-8.39deg,
			transparent 0%, transparent 41%,
			rgba(30, 160, 172, 0.05) 41%, rgba(30, 160, 172, 0.05) calc(41% + 10px),
			transparent calc(41% + 10px), transparent calc(41% + 20px),
			rgba(30, 160, 172, 0.1) calc(41% + 20px), rgba(30, 160, 172, 0.1) calc(41% + 30px),
			transparent calc(41% + 30px), transparent calc(41% + 40px),
			rgba(30, 160, 172, 0.4) calc(41% + 40px), rgba(30, 160, 172, 0.4) calc(41% + 50px),
			transparent calc(41% + 50px), transparent calc(41% + 60px),
			rgba(30, 160, 172, 0.7) calc(40% + 60px), rgba(30, 160, 172, 0.7) calc(41% + 70px),
			transparent calc(41% + 70px), transparent 100%);
		background-repeat: repeat-x;
		padding: 64px 0 calc(64px + 40px);
		position: relative;
	}

	.wp-block-tenup-most-popular {
		max-width: 1300px;
		margin: 0 auto;
	}

	.most-popular--grid {
		display: grid;
		grid-template-columns: 1fr;
		grid-gap: 39px;
	}

	@media (min-width: 48em) {
		.most-popular--grid {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media ( min-width: 64em) {
		.most-popular--grid {
			grid-template-columns: repeat(4, 1fr);
		}
	}

	.most-popular--item:nth-child(2n) {
		margin-top: 40px;
		margin-bottom: -40px;
	}

	.most-popular--item {
		background-color: #FFFFFF;
		border-bottom: 4px solid #1EA0AC;
		padding: 16px 16px 24px;
	}

	.most-popular--item > img {
		margin-bottom: 16px;
	}

	.most-popular--item > a {
		color: #27292B;
		font-family: 'Bitter';
		font-style: normal;
		font-weight: 600;
		font-size: 20px;
		line-height: 130%;
	}

	.most-popular--item-grades {
		color: #565867;
		font-size: 14px;
		font-weight: 700;
		letter-spacing: 0.03em;
		margin-bottom: 8px;
		text-transform: uppercase;
	}

	.most-popular--item-meta {
		align-items: center;
		display: flex;
		justify-content: space-between;
		margin-top: 15px;
		text-transform: uppercase;
	}

	.most-popular--item-meta > span {
		font-size: 14px;
		font-weight: 600;
		letter-spacing: 0.03em;
		color: #27292B;
		background-color: #E9EAEC;
		padding: 4px 8px;
	}

	.most-popular--item-meta > div > span {
		font-weight: 700;
	}
</style>

<div class="container">
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae sagittis sem. Quisque dictum, mi et pulvinar volutpat, enim lacus rutrum libero, sed ornare orci diam ullamcorper diam. Quisque auctor dignissim tortor eget dapibus. Praesent vulputate facilisis ex, at ultricies diam sollicitudin nec. Vestibulum tincidunt ipsum arcu, vel eleifend leo tincidunt non. Nam fringilla orci eget tempor ultrices. Proin scelerisque pharetra dolor ac interdum. In semper odio a mollis porttitor. Nunc eget quam rutrum lorem ornare vehicula. Sed non pharetra nunc, vel pharetra est. In eget risus elit. Vivamus fermentum lacus a nibh gravida, vel ultrices risus fermentum.</p>

	<p>Integer et ex molestie, efficitur sem vel, semper neque. Morbi bibendum quis nibh ut convallis. Vestibulum mattis euismod eros, a blandit velit pharetra sit amet. Vestibulum gravida iaculis interdum. Aliquam consequat luctus massa, et dapibus tortor blandit eget. Mauris vel eros eget ipsum faucibus lobortis. Cras lacinia malesuada congue. Maecenas tincidunt arcu orci, quis sodales elit molestie a.</p>

	<p>Curabitur risus neque, efficitur nec volutpat a, pretium ac mauris. Nullam dignissim maximus ultricies. Donec tempus, purus a pharetra euismod, velit est congue nunc, congue vestibulum lectus metus non velit. Fusce sit amet blandit arcu. Donec rutrum neque sed euismod euismod. Etiam ut fermentum elit. Curabitur nec dui tellus. Phasellus lobortis, ex at rutrum feugiat, felis diam venenatis enim, non euismod orci mi et ex. Sed maximus velit eget blandit pulvinar. Vivamus sit amet augue massa. Maecenas tincidunt, odio sed aliquam cursus, odio nunc tincidunt metus, vel cursus risus lorem nec est. Curabitur eget fermentum nisi, non dictum quam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Integer erat ipsum, mollis a aliquam nec, lacinia sed lectus. Etiam aliquet risus vitae tristique pharetra.</p>

	<p>Nunc ultricies erat eget erat lacinia, sit amet dapibus augue efficitur. Ut id nulla vel neque placerat interdum. Maecenas eu metus sollicitudin, rhoncus augue vitae, ornare nulla. Sed varius, nibh nec pellentesque pretium, nisi est eleifend purus, id vulputate eros ex et arcu. Vestibulum ante odio, efficitur a neque at, sodales congue libero. Sed lacus diam, maximus sit amet feugiat a, auctor et quam. Donec hendrerit sapien fermentum ante tristique, eget efficitur velit dignissim. Curabitur nec tristique nunc, at consectetur dolor.</p>

	<p style="margin-bottom: 0">Sed tincidunt lobortis fringilla. Nam quis ultricies mi. Vivamus id turpis non nunc rhoncus gravida. Morbi auctor scelerisque lobortis. Mauris posuere sit amet nunc nec sollicitudin. Phasellus convallis lorem quis tortor imperdiet eleifend. Maecenas at magna euismod, gravida est a, scelerisque tortor. Pellentesque non vestibulum lorem. Sed diam magna, consectetur sit amet finibus et, gravida et justo. Proin aliquet mollis tortor, vel efficitur elit varius quis. Morbi lacus nisi, ornare sit amet massa sit amet, accumsan pellentesque augue. Nulla dictum nisl nulla. Suspendisse malesuada augue et velit dictum scelerisque non at nisi. Duis vestibulum tempor quam non tempus. Nam id enim lacinia, cursus nunc a, maximus augue.</p>
</div>

<div class="stripe-background">
	<div class="wp-block-tenup-most-popular">
		<h2>Most Popular</h2>

		<div class="most-popular--grid">
			<div class="most-popular--item">
				<img src="https://placehold.co/264x148" alt="">
				<div class="most-popular--item-grades">
					Grades: <a href="#">K-5</a>
				</div>
				<a href="#">Enim fringilla nam curabitur ultrices nulla nullam sed at.</a>
				<div class="most-popular--item-meta">
					<span>math</span>
					<div>
						<span>153</span> reads
					</div>
				</div>
			</div>
			<div class="most-popular--item">
				<img src="https://placehold.co/264x148" alt="">
				<div class="most-popular--item-grades">
					Grades: <a href="#">K-5</a>
				</div>
				<a href="#">Nulla commodo arcu nec sagittis habitasse facilisi gravida nulla.</a>
				<div class="most-popular--item-meta">
					<span>science</span>
					<div>
						<span>68</span> reads
					</div>
				</div>
			</div>
			<div class="most-popular--item">
				<img src="https://placehold.co/264x148" alt="">
				<div class="most-popular--item-grades">
					Grades: <a href="#">K-5</a>
				</div>
				<a href="#">Malesuada pharetra sed sed nam nisi ac eu viverra. Risus ultrices.</a>
				<div class="most-popular--item-meta">
					<span>social studies</span>
					<div>
						<span>71</span> reads
					</div>
				</div>
			</div>
			<div class="most-popular--item">
				<img src="https://placehold.co/264x148" alt="">
				<div class="most-popular--item-grades">
					Grades: <a href="#">K-5</a>
				</div>
				<a href="#">Enim fringilla nam curabitur ultrices nulla nullam sed at.</a>
				<div class="most-popular--item-meta">
					<span>math</span>
					<div>
						<span>68</span> reads
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae sagittis sem. Quisque dictum, mi et pulvinar volutpat, enim lacus rutrum libero, sed ornare orci diam ullamcorper diam. Quisque auctor dignissim tortor eget dapibus. Praesent vulputate facilisis ex, at ultricies diam sollicitudin nec. Vestibulum tincidunt ipsum arcu, vel eleifend leo tincidunt non. Nam fringilla orci eget tempor ultrices. Proin scelerisque pharetra dolor ac interdum. In semper odio a mollis porttitor. Nunc eget quam rutrum lorem ornare vehicula. Sed non pharetra nunc, vel pharetra est. In eget risus elit. Vivamus fermentum lacus a nibh gravida, vel ultrices risus fermentum.</p>

	<p>Integer et ex molestie, efficitur sem vel, semper neque. Morbi bibendum quis nibh ut convallis. Vestibulum mattis euismod eros, a blandit velit pharetra sit amet. Vestibulum gravida iaculis interdum. Aliquam consequat luctus massa, et dapibus tortor blandit eget. Mauris vel eros eget ipsum faucibus lobortis. Cras lacinia malesuada congue. Maecenas tincidunt arcu orci, quis sodales elit molestie a.</p>

	<p>Curabitur risus neque, efficitur nec volutpat a, pretium ac mauris. Nullam dignissim maximus ultricies. Donec tempus, purus a pharetra euismod, velit est congue nunc, congue vestibulum lectus metus non velit. Fusce sit amet blandit arcu. Donec rutrum neque sed euismod euismod. Etiam ut fermentum elit. Curabitur nec dui tellus. Phasellus lobortis, ex at rutrum feugiat, felis diam venenatis enim, non euismod orci mi et ex. Sed maximus velit eget blandit pulvinar. Vivamus sit amet augue massa. Maecenas tincidunt, odio sed aliquam cursus, odio nunc tincidunt metus, vel cursus risus lorem nec est. Curabitur eget fermentum nisi, non dictum quam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Integer erat ipsum, mollis a aliquam nec, lacinia sed lectus. Etiam aliquet risus vitae tristique pharetra.</p>

	<p>Nunc ultricies erat eget erat lacinia, sit amet dapibus augue efficitur. Ut id nulla vel neque placerat interdum. Maecenas eu metus sollicitudin, rhoncus augue vitae, ornare nulla. Sed varius, nibh nec pellentesque pretium, nisi est eleifend purus, id vulputate eros ex et arcu. Vestibulum ante odio, efficitur a neque at, sodales congue libero. Sed lacus diam, maximus sit amet feugiat a, auctor et quam. Donec hendrerit sapien fermentum ante tristique, eget efficitur velit dignissim. Curabitur nec tristique nunc, at consectetur dolor.</p>

	<p>Sed tincidunt lobortis fringilla. Nam quis ultricies mi. Vivamus id turpis non nunc rhoncus gravida. Morbi auctor scelerisque lobortis. Mauris posuere sit amet nunc nec sollicitudin. Phasellus convallis lorem quis tortor imperdiet eleifend. Maecenas at magna euismod, gravida est a, scelerisque tortor. Pellentesque non vestibulum lorem. Sed diam magna, consectetur sit amet finibus et, gravida et justo. Proin aliquet mollis tortor, vel efficitur elit varius quis. Morbi lacus nisi, ornare sit amet massa sit amet, accumsan pellentesque augue. Nulla dictum nisl nulla. Suspendisse malesuada augue et velit dictum scelerisque non at nisi. Duis vestibulum tempor quam non tempus. Nam id enim lacinia, cursus nunc a, maximus augue.</p>
</div>

<?php
get_footer();
