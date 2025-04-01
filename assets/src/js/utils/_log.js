export const devLog = (...args) => {
  if (process.env.NODE_ENV === "development" || window.isDevelopment) {
    console.log(...args);
  }
};
