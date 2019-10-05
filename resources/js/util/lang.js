export const MOMENT_DATETIME_FORMAT = 'YYYY-MM-DD hh:mm:ss';
export const MOMENT_ISO_DATE_FORMAT = 'YYYY-MM-DD';

export function csvContains(target, searchValue, delimiter) {
  return (
    -1 !==
    (delimiter + target + delimiter).indexOf(
      delimiter + searchValue + delimiter
    )
  );
}
