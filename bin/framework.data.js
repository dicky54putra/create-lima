const {
  blue,
  cyan,
  green,
  lightGreen,
  lightRed,
  magenta,
  red,
  reset,
  yellow,
} = require("kolorist");

const FRAMEWORKS = [
  {
    name: "next",
    display: "NEXT JS",
    color: yellow,
    variants: [
      {
        name: "next",
        display: "JavaScript",
        color: yellow,
      },
      {
        name: "next-ts",
        display: "TypeScript",
        color: blue,
      },
    ],
  },
  {
    name: "wp-theme-elementor",
    display: "WP THEME ELEMENTOR",
    color: blue,
  },
];

module.exports = FRAMEWORKS;
