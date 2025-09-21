import Plyr from 'plyr';
import 'plyr/dist/plyr.css'

export default function () {
  return {
    player: undefined,
    init() {
      this.player = new Plyr(this.$el, {
        controls: [
          'play-large',
          'play',
          'progress',
          'current-time',
          'mute',
          'volume',
          'captions',
          // 'settings',
          'pip',
          'airplay',
          'fullscreen',
        ],
      })
    },
  }
}
