# Releasing

1. Update `addon/VERSION`.
2. Ensure runtime version in `addon/dist/custom_js.html` if used.
3. Commit and tag: `vX.Y.Z`.
4. Push tag.

Release workflow will publish:
- `dist/nerdcore-addon-vX.Y.Z.zip`
