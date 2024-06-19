<template>
  <v-sheet class="card pa-0" width="520" height="276" color="card-dark"  >
      <v-img
          src="/assets/svg/WithdrawlsCard.svg"
      />
      <v-sheet class="content pa-8" color="transparent">
          <div class="left">
              <p class="date">27.01.2023</p>
              <div>
                  <h4 class="card-name">Ваш баланс</h4>
                  <p class="balance">{{balance}} руб.</p>
              </div>
          </div>
          <v-sheet color="common-green" class="right">
              <label class="label" style="--p:60;--c: #C8C8C8">
                  <p class="amount">{{withdrawls}} $</p>
                  <h5 class="description">Выведено</h5>
                  <progress class="progress" max="100" value="60">60%</progress>
              </label>
          </v-sheet>
      </v-sheet>
  </v-sheet>
</template>

<script>

export default {
    name: "WithdrawlsCard",
    props: {
        title: {
            type: String,
            default: ''
        },
        balance: {
            type: String,
            default: 0
        },
        withdrawls: {
            type: String,
            default: 0
        }
    },
}
</script>

<style scoped>
.card {
    font-family: "Roboto Flex Variable", sans-serif;
    border-radius: 14px;
    padding: 18px;
    position: relative;
}

.card-name {
    height: 20px;
}

.content {
    display: flex;
    justify-content: space-between;
    position: absolute;
    text-align: start;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    .left {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;

        .date {
            font-size: 20px;
            line-height: 20px;
            font-weight: 200;
        }

        .card-name {
            font-size: 18px;
            font-weight: 500;
            line-height: 20px;
            margin-bottom: 14px;
        }

        .balance {
            font-weight: 600;
            height: 20px;
            font-size: 24px;
        }
    }

    .right {
        width: 150px;
        height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        border-radius: 50%;
        position: absolute;
        top: 30%;
        left: 51%;

        .amount {
            line-height: 20px;
            font-size: 22px;
            font-weight: 500;
            margin-bottom: 4px;
            max-width: 120px;
        }

        .description {
            line-height: 20px;
            font-weight: 500;
            font-size: 14px;
            max-width: 120px;
            color: #DDDDDD;
        }
    }
}

progress[value] {
    --background:
        radial-gradient(50% 50%,var(--c) 98%,#0000) top/10% 10% no-repeat,
        conic-gradient(var(--c) calc(var(--p)*1%),#0000 0);

    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border: none;
    position: absolute;
    inset: 0;
    height: 100%;
    width: 100%;
    border-radius: 50%;
    overflow: hidden;
    background: var(--background);
    -webkit-mask: radial-gradient(50% 50%,#0000 79%,#000 80% 100%,#0000 102%)
}
.progress[value]::-webkit-progress-bar {
    background: var(--background);
}
.progress[value]::-webkit-progress-value {
    background: #0000;
}
.progress[value]::-moz-progress-bar {
    background: #0000;
}

label {
    --w: 150px;    /* the width*/
    --c: #31c6f7;  /* the color */

    font-size: 20px;
    font-weight: bold;
    display: inline-grid;
    place-content: center;
    width: var(--w);
    aspect-ratio: 1;
    position: relative;
}

label::before {
    content: "";
    position: absolute;
    border-radius: 50%;
    inset: 45%;
    background: var(--c);
    transform: rotate(calc(var(--p)*3.6deg)) translateY(calc(50% - var(--w)/2));
}

</style>
