<template>
  <div class="navbar-item has-dropdown is-hoverable">
    <a class="navbar-item">
      <b-icon
        class="badge"
        data-badge="5"
        slot="trigger"
        icon="bell-o">
      </b-icon>
    </a>
    <div class="navbar-dropdown is-right navbar-noty">
        <a class="navbar-item" href="/2017/11/14/bulma-is-on-patreon/">
          <div class="navbar-content">
            <p>
              <small class="has-text-link has-text-primary">14 Nov 2017</small>
            </p>
            <p>Bulma is on Patreon!</p>
          </div>
        </a>
        <hr class="navbar-divider">
        <a class="navbar-item" href="/2017/11/01/fixed-navbar/">
          <div class="navbar-content">
            <p>
              <small class="has-text-link">01 Nov 2017</small>
            </p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed voluptates amet perspiciatis possimus repellat dolore aut eos beatae consectetur velit!</p>
          </div>
        </a>
        <hr class="navbar-divider">
        <a class="navbar-item" href="/2017/10/22/list-of-buttons/">
          <div class="navbar-content">
            <p>
              <small class="has-text-link">22 Oct 2017</small>
            </p>
            <p>New feature: list of buttons</p>
          </div>
        </a>
        <hr class="navbar-divider">
      <a class="navbar-item" href="https://bulma.io/blog/">
        More posts
      </a>
      <hr class="navbar-divider">
      <div class="navbar-item">
        <div class="navbar-content">
          <div class="level is-mobile">
            <div class="level-left">
              <div class="level-item">
                <strong>Stay up to date!</strong>
              </div>
            </div>
            <div class="level-right">
              <div class="level-item">
                <a class="button bd-is-rss is-small" href="https://bulma.io/atom.xml">
                  <span class="icon is-small">
                    <i class="fa fa-rss"></i>
                  </span>
                  <span>Subscribe</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    mounted() {
      console.log(this.$store.getters.authUser.id)
      Echo.private('users.' + this.$store.getters.authUser.id)
        .notification((notification) => {
          console.log(notification);
        });
    }
  }
</script>
