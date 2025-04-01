<article id="page-404" <?php post_class( ['blocks'] ); ?>>
    <section id="block-404" class="bg-primary py-10">
      <div class="container">
        <div class="row">
          <div class="col-12 d-flex flex-column align-items-center text-white">

            <h1 class="fw-bolder">
              <?php TST___e('404'); ?>
            </h1>

            <h2 class="text-center">
              <?php TST___e('La pagina che hai cercato non esiste...'); ?>
            </h2>

            <a class="btn btn-secondary d-flex align-items-center text-nowrap text-primary text-uppercase mt-5"
              href="<?php echo get_home_url(); ?>"
              target="_self">Homepage
              <i
                class="icon-arrow-right bg-dark text-white ms-3 rounded p-1 d-flex justify-content-center align-items-center"></i>
            </a>
          </div>
        </div>
      </div>
    </section>
</article><!-- #page-404 -->